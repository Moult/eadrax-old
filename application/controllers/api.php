<?php
/**
 * Eadrax
 *
 * Eadrax is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Eadrax is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *                                                                                
 * You should have received a copy of the GNU General Public License
 * along with Eadrax; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category	Eadrax
 * @package		API
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Implementation of the OCS REST API
 *
 * For more information: http://socialdesktop.org/
 * This is based loosely upon lib_ocs (PHP serverside implementation)
 *
 * @category	Eadrax
 * @package		API
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Api_Controller extends Core_Controller {

	public static $whitelist = array('127.0.0.1', 'x.x.x.x');
	public static $maxpersonsearchpage = 20;
	public static $maxrequests = 200; # per 15m per IP
	public static $maxrequestsauthenticated = 400;
	
	public function index()
	{
		die();
	}

	/**
	 * Reads data specified by $key from various get/post/default sources.
	 *
	 * @param string $key		The key to refer to the variable
	 * @param string $type		The data type of the variable
	 * @param bool $getpriority	The priority of the variable
	 * @param mixed $default	The default value of the variable
	 *
	 * @return mixed
	 */
	public function readdata($key, $type = 'raw', $getpriority = FALSE, $default = '')
	{
		if ($getpriority) {
			if (isset($_GET[$key])) {
				$data = $_GET[$key];
			} elseif (isset($_POST[$key])) {
				$data = $_POST[$key];
			} else {
				if ($default == '') {
					if (($type == 'int') || ($type == 'float')) {
						$data = 0;
					} else {
						$data = '';
					}
				} else {
					$data = $default;
				}
			}
		} else {
			if (isset($_POST[$key])) {
				$data = $_POST[$key];
			} elseif (isset($_GET[$key])) {
				$data = $_POST[$key];
			} else {
				if ($default == '') {
					if (($type == 'int') || ($type == 'float')) {
						$data = 0;
					} else {
						$data = '';
					}
				} else {
					$data = $default;
				}
			}
		}

		if ($type == 'raw' || $type == 'array') {
			return $data;
		} elseif ($type == 'text') {
			return addslashes(strip_tags($data));
		} elseif ($type == 'int') {
			$data = (int) $data;
			return $data;
		} elseif ($type == 'float') {
			$data = (float) $data;
			return $data;
		} else {
			H01_UTIL::exception('readdata: internal error:'. $type);
			return FALSE;
		}
	}

	/**
	 * Main function to handle the REST request
	 */
	public function handle() {
		// Overwrite the 404 error page returncode
		header('HTTP/1.0 200 OK');

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$method = 'get';
		} elseif($_SERVER['REQUEST_METHOD'] == 'PUT') {
			$method = 'put';
			parse_str(file_get_contents('php://input'), $put_vars);
		} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$method = 'post';
		} else {
			echo 'internal server error: method not supported';
			exit();
		}

		// Preprocess URL
		$url = $_SERVER['PHP_SELF'];

		// Must end in a /
		if (substr($url, (strlen($url)-1)) != '/') {
			$url .= '/';
		}

		$ex = explode('/', $url);

		// Eventhandler
		if (count($ex) == 2) {
			H01_GUI::showtemplate('apidoc');
		} elseif ($method == 'get' && strtolower($ex[1]) == 'v1' && strtolower($ex[2]]) == 'config' && count($ex[$4])) {
			$format = H01_OCS::readdata('format', 'text');
			H01::OCS::apiconfig($format);
		} else {
			$format = H01_OCS::readdata('format', 'text');
			$txt = 'please check the syntax. api specifications are here: http://www.freedesktop.org/wiki/Specifications/open-collaboration-services' . "\n";
			$text .= H01_OCS::getdebugoutput();
		}
		exit();
	}

	/**
	 * Generates debug information to make it easier to find failed API calls
	 *
	 * @return string
	 */
	private function getdebugoutput() {
		$txt = 'debug outout: ' . "\n";
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$txt .= 'http request method: '. $_SERVER['REQUEST_METHOD'] ."\n";
		}
		if (isset($_SERVER['REQUEST_URI'])) {
			$txt .= 'http request uri: '. $_SERVER['REQUEST_URI'] ."\n";
		}
		if (isset($_GET)) {
			foreach ($_GET as $key => $value) {
				$txt .= 'get parameter: '. $key .'->'. $value ."\n";
			}
		}
		if (isset($_POST)) {
			foreach ($_POST as $key => $value) {
				$txt .= 'post parameter: '. $key .'->'. $value ."\n";
			}
		}
		return $txt;
	}

	/**
	 * Checks if the user is authenticated.
	 * Checks IP whitelist, API keys and login/password combination
	 * If $forceuser == TRUE, authentication failed, it returns 401.
	 * If $forceuser == FALSE, authentication failed, it returns empty username 
	 *
	 * @param bool $forceuser
	 *
	 * @return string
	 */
	private function checkpassword($forceuser = TRUE) {
		// Check whitelist first.
		if (in_array($_SERVER['REMOTE_ADDR'], H01_OCS::$whitelist)) {
			$identifieduser = '';
		} else {
			// is it a valid user account?
			if (isset($_SERVER['PHP_AUTH_USER'])) {
				$authuser = $_SERVER['PHP_AUTH_USER'];
			} else {
				$authuser = '';
			}

			if (isset($_SERVER['PHP_AUTH_PW'])) {
				$authpw = $_SERVER['PHP_AUTH_PW'];
			} else {
				$authpw = '';
			}

			if (empty($authuser)) {
				if ($forceuser) {
					header('WWW-Authenticate: Basic realm="your valid user account or api key"');
					header('HTTP/1.0 401 Unauthorized');
					exit;
				} else {
					$identifieduser = '';
				}
			} else {
				$user = H01_USER::finduserbyapikey($authuser, CONFIG_USERDB);
				if ($user == FALSE) {
					$user = H01_USER::checklogin($authuser, CONFIG_USERDB, $authpw, PERM_Login);
					if ($user == false) {
						if ($forceuser) {
							header('WWW-Authenticate: Basic realm="your valid user account or api key"');
							header('HTTP/1.0 401 Unauthorized');
							exit;
						} else {
							$identifieduser = '';
						}
					} else {
						$identifieduser = $user;
					}
				} else {
					$identifieduser = $user;
				}
			}
		}

		return $identifieduser;
	}

	/**
	 * Cleans up the API traffic limit database table.
	 * Should be called by a cronjob every 15 minutes.
	 */
	public function cleanuptrafficlimit() {
		$result = H01_DB::query('truncate apitraffic');
		H01_DB::freeresult($result);
	}

	/**
	 * Checks if the current user is allowed to do another API call or if the 
	 * traffic has been exceeded.
	 *
	 * @param string $user
	 *
	 * @return bool
	 */
	private function checktrafficlimit($user) {
		$result = H01_DB::insert('apitraffic', 'into apitraffic (ip, count) values ('. ip2long($_SERVER['REMOTE_ADDR']) .',1) on duplicate key update count=count+1');
		H01_DB::free_result($result);

		$result = H01_DB::select('apitraffic', 'count from apitraffic where ip="'. ip2long($_SERVER['REMOTE_ADDR']) .'"');
		$numrows = H01_DB::numrows($result);
		$DBCount = H01_DB::fetch_assoc($result);
		H01_DB::free_result($result);

		if ($numrows == 0) {
			return TRUE;
		}

		if ($user == '') {
			$max = H01_OCS::$maxrequests;
		} else {
			$max = H01_OCS::$maxrequestsauthenticated;
		}

		if ($DBCount['count'] > $max) {
			$format = H01_OCS::readdata('format', 'text');
			echo H01_OCS::generatexml($format, 'failed', 200, 'too many API requests in the last 15 minutes from your IP address. please try again later.');
			exit();
		}

		return TRUE;
	}

	/**
	 * Generates the XML or JSON response for the API call from a 
	 * multidimensional data array.
	 *
	 * @param string $format
	 * @param string $status
	 * @param string $statuscode
	 * @param string $message
	 * @param array $data
	 * @param string $tag
	 * @param string $tagattribute
	 * @param int $dimension
	 * @param int $itemscount
	 * @param int $itemsperpage
	 *
	 * @return string xml/json
	 */
	private function generatexml($format, $status, $statuscode, $message, $data = array(), $tag = '', $tagattribute = '', $dimension = -1, $itemscount = '', $itemsperpage = '') {
		if ($format == 'json') {
			$json = array();
			$json['status'] = $status;
			$json['statuscode'] = $statuscode;
			$json['message'] = $message;
			$json['totalitems'] = $itemscount;
			$json['itemsperpage'] = $itemsperpage;
			$json['data'] = $data;

			return json_encode($json);
		} else {
			$writer = xmlwriter_open_memory();

			xmlwriter_set_indent($writer, 2);
			xmlwriter_start_document($writer);
			xmlwriter_start_element($writer, 'ocs');
			xmlwriter_start_element($writer, 'meta');
			xmlwriter_start_element($writer, 'status', $status);
			xmlwriter_start_element($writer, 'statuscode', $statuscode);
			xmlwriter_start_element($writer, 'message', $message);

			if ($itemscount != '') {
				xmlwriter_write_element($writer, 'totalitems', $itemscount);
			}

			if (!empty($itemsperpage)) {
				xmlwriter_write_element($writer, 'itemsperpage', $itemsperpage);
			}

			xmlwriter_end_element($writer);

			if ($dimension == 0) {
				// 0 dimensions
				xmlwriter_write_element($writer, 'data', $data);
			} elseif ($dimension == 1) {
				xmlwriter_start_element($writer, 'data');
				foreach ($data as $key => $entry) {
					xmlwriter_write_element($writer, $key, $entry);
				}
				xmlwriter_end_element($writer);
			} elseif ($dimension == 2) {
				xmlwriter_start_element($writer, 'data');
				foreach ($data as $entry) {
					xmlwriter_start_element($writer, $tag);

					if (!empty($tagattribute)) {
						xmlwriter_write_attribute($writer, 'details', $tagattribute);
					}

					foreach ($entry as $key => $value) {
						if (is_array($value)) {
							foreach ($value as $k => $v) {
								xmlwriter_write_element($writer, $k, $v);
							}
						} else {
							xmlwriter_write_element($writer, $key, $value);
						}
					}

					xmlwriter_end_element($writer);
				}

				xmlwriter_end_element($writer);
			}

			xmlwriter_end_element($writer);

			xmlwriter_end_document($writer);

			$txt = xmlwriter_output_memory($writer);
			unset($writer);

			return $txt;
		}
	}

	/**
	 * Return the config data of this server
	 *
	 * @param string $format
	 * 
	 * @return string xml/json
	 */
	private function apiconfig($format) {
		$user = H01_OCS::checkpassword(FALSE);
		H01_OCS::checktrafficlimit($user);

		$xml['version'] = '1.4';
		$xml['website'] = 'openDesktop.org';
		$xml['host'] = 'api.openDesktop.org';
		$xml['contact'] = 'frank@openDesktop.org';
		$xml['ssl'] = 'true';

		echo H01_OCS::generatexml($format, 'ok', 100, '', $xml, 'config', '', 1);
	}

	// NOW WE BEGIN FUNCTIONS FOR API CALLS
}
