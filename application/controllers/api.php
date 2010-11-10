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
 * This is based upon lib_ocs (PHP serverside implementation)
 *
 * @category	Eadrax
 * @package		API
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Api_Controller extends Core_Controller {

	//public $whitelist = array('127.0.0.1', 'x.x.x.x');
	public $whitelist = array('x.x.x.x');
	public $maxpersonsearchpage = 20;
	public $maxrequests = 200; # per 15m per IP
	public $maxrequestsauthenticated = 400;
	public $format = 'xml';
	
	public function index()
	{
		die('<a href="http://www.freedesktop.org/wiki/Specifications/open-collaboration-services">OCS v1</a> is currently implemented.');
	}

	/**
	 * Main function to handle the REST request
	 */
	public function v1($module = NULL, $call = '') {
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

		if ($this->input->get('format', NULL) != NULL) {
			$this->format = $this->input->get('format');
		} elseif ($this->input->post('format', NULL) != NULL) {
			$this->format = $this->input->post('format');
		} else {
			$this->format = 'xml';
		}

		// Find out if we have a valid module and call.
		if (valid::alpha($module) == FALSE || (!empty($call) && valid::alpha($call) == FALSE)) {
			$api_call = FALSE;
		} else {
			if (empty($call)) {
				$api_call = $module .'_'. $method;
			} else {
				$api_call = $module .'_'. $call .'_'. $method;
			}

			if (method_exists($this, $api_call) != TRUE) {
				$api_call = FALSE;
			}
		}

		// Eventhandler
		if ($this->uri->total_segments() == 1) {
			echo 'showing the apidoc template';
		} elseif ($api_call != FALSE) {
			$this->$api_call($this->format);
		} else {
			$txt = 'please check the syntax. api specifications are here: http://www.freedesktop.org/wiki/Specifications/open-collaboration-services' . "\n";
			$txt .= $this->getdebugoutput();

			echo $this->generatexml($this->format, 'failed', 999, $txt);
		}
		exit();
	}

	/**
	 * Generates an API key for a user after getting them to agree to basic ToS.
	 */
	public function generate() {
		// We only want logged in people.
		$this->restrict_access();

		// Load necessary models.
		$user_model = new User_Model;

		$user_info = $user_model->user_information($this->uid);
		$apikey = $user_info['apikey'];

		if ($this->input->post() && empty($apikey))
		{
			$agree = $this->input->post('agree');

			// ...and we continue doing normal registration.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('agree', 'required');

			if ($validate->validate())
			{
				// Everything went great! Let's generate.
				$apikey = '';

				for ($n = 0; $n < 3; $n++) {
					// can't handle numbers larger than 2^31-1 = 2147483647
					$rand = rand(1000000, 2147483647);
					$base = 62;
					$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$str = '';

					do {
						$i = $rand % 62;
						$str = $chars[$i] . $str;
						$rand = ($rand - $i) / 62;
					} while($rand > 0);

					$apikey .= $str;
				}

				$user_model->manage_user(array('apikey' => $apikey), $this->uid);

				$this->session->set('notification', 'Your API key has been generated. Wicked.');
				url::redirect(url::base() .'api/generate/');
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$generate_view = new View('generate');
				$generate_view->form	= arr::overwrite(array(
					'agree' => '',
					), $validate->as_array());
				$generate_view->errors	= $validate->errors('generate_errors');

				// Generate the content.
				$this->template->content = array($generate_view);
			}
		}
		else
		{
			// Load the neccessary view.
			$generate_view = new View('generate');

			// If we didn't press submit, we want a blank form.
			$generate_view->form = array('agree'=>'');

			$generate_view->apikey = $apikey;

			// Generate the content.
			$this->template->content = array($generate_view);
		}
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
		// Load necessary models.
		$user_model = new User_Model;

		// Check whitelist first.
		if (in_array($_SERVER['REMOTE_ADDR'], $this->whitelist)) {
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
				// Finds the corresponding row for the user in our database (API 
				// key auth).
				$user = $user_model->username($authuser);
				if ($user == FALSE) {
					// If not found, check login using a special function (USER/PASS auth)
					$authlite = new Authlite();
					if ($authlite->login($authuser, $authpw, FALSE)) {
						$user = $authlite->get_user()->username;
					} else {
						$user = FALSE;
					}

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
		$api_model = new Api_Model;

		$api_model->truncate();
	}

	/**
	 * Checks if the current user is allowed to do another API call or if the 
	 * traffic has been exceeded.
	 *
	 * @param string $user
	 *
	 * @return bool
	 */
	private function checktrafficlimit($user = '') {
		$api_model = new Api_Model;

		$count = $api_model->add_traffic($_SERVER['REMOTE_ADDR']);

		if ($user == '') {
			$max = $this->maxrequests;
		} else {
			$max = $this->maxrequestsauthenticated;
		}

		if ($count > $max) {
			echo $this->generatexml($this->format, 'failed', 200, 'too many API requests in the last 15 minutes from your IP address. please try again later.');
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
			xmlwriter_write_element($writer, 'status', $status);
			xmlwriter_write_element($writer, 'statuscode', $statuscode);
			xmlwriter_write_element($writer, 'message', $message);

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
	private function config_get($format) {
		$user = $this->checkpassword(FALSE);

		$this->checktrafficlimit($user);

		$xml['version'] = '1.4';
		$xml['website'] = 'wipup.org';
		$xml['host'] = 'wipup.org';
		$xml['contact'] = 'dion@thinkmoult.com';
		$xml['ssl'] = 'false';

		echo $this->generatexml($format, 'ok', 100, '', $xml, 'config', '', 1);
	}

	// NOW WE BEGIN OUR FUNCTIONS FOR API CALLS
}
