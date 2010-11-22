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

	// APIs don't need fancy pants views.
	public $auto_render = FALSE;
	
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
			$this->$api_call();
		} else {
			$txt = 'please check the syntax. api specifications are here: http://www.freedesktop.org/wiki/Specifications/open-collaboration-services' . "\n";
			$txt .= $this->getdebugoutput();

			echo $this->generatexml('failed', 999, $txt);
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
			echo $this->generatexml('failed', 200, 'too many API requests in the last 15 minutes from your IP address. please try again later.');
			exit();
		}

		return TRUE;
	}

	/**
	 * Generates the XML or JSON response for the API call from a 
	 * multidimensional data array.
	 *
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
	private function generatexml($status, $statuscode, $message, $data = array(), $tag = '', $tagattribute = '', $dimension = -1, $itemscount = '', $itemsperpage = '') {
		if ($this->format == 'json') {
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

	// NOW WE BEGIN OUR FUNCTIONS FOR API CALLS

	/**
	 * Return the config data of this server
	 *
	 * @return string xml/json
	 */
	private function config_get() {
		$user = $this->checkpassword(FALSE);
		$this->checktrafficlimit($user);

		$xml['version'] = '1.4';
		$xml['website'] = 'wipup.org';
		$xml['host'] = 'wipup.org';
		$xml['contact'] = 'dion@thinkmoult.com';
		$xml['ssl'] = 'false';

		echo $this->generatexml('ok', 100, '', $xml, 'config', '', 1);
	}

	private function person_check_post() {
		$user = $this->checkpassword(FALSE);
		$this->checktrafficlimit($user);

		$user_model = new User_Model;

		$login = $this->input->post('login', NULL);
		$password = $this->input->post('password', NULL);

		$user = $user_model->username($login);
		if ($login == NULL || ($user == FALSE && $password == NULL)) {
			echo $this->generatexml('failed',101,'please specify all mandatory fields');
		} else {
			if ($user == FALSE) {
				// If not found, check login using a special function (USER/PASS auth)
				$authlite = new Authlite();
				if ($authlite->login($login, $password, FALSE)) {
					$user = $authlite->get_user()->username;
				} else {
					$user = FALSE;
				}
			}

			if ($user == FALSE) {
				echo $this->generatexml('failed',102,'login not valid');
			} else {
				$xml['person']['personid'] = $user;
				echo $this->generatexml('ok',100,'',$xml,'person','check',2); 
			}
		}
	}

	private function person_add_post() {
		$user = $this->checkpassword(FALSE);
		$this->checktrafficlimit($user);

		// Reroute POST vars.
		$_POST['openid_identifier'] = $this->input->post('login');

		// Run our existing controller function.
		Users_Controller::register();

		// Remap vars from original controller.
		if (isset($this->template->content)) {
			$errors = isset($this->template->content[0]->errors) ? $this->template->content[0]->errors : NULL;
		} else {
			$errors = NULL;
		}
		$openid_identifier = isset($errors['openid_identifier']) ? $errors['openid_identifier'] : NULL;
		$password = isset($errors['password']) ? $errors['password'] : NULL;

		$error_msgs = Kohana::lang('register_errors');

		if ( $errors == NULL) {
			echo $this->generatexml('ok',100,'');
		} elseif (
			$openid_identifier == $error_msgs['openid_identifier']['required'] || 
			$password == $error_msgs['password']['required']
		) {
			echo $this->generatexml('failed',101,'please specify all mandatory fields');
		} elseif (
			$openid_identifier == $error_msgs['openid_identifier']['length'] || 
			$openid_identifier == $error_msgs['openid_identifier']['alpha_dash']
		) {
			echo $this->generatexml('failed',103,'please specify a valid login');
		} elseif (
			$openid_identifier == $error_msgs['openid_identifier']['unique']
		) {
			echo $this->generatexml('failed',104,'login already exists');
		}
	}

	private function person_data_get() {
		$personid = $this->uri->segment(5);

		// This means it's PERSON->Get(self)
		if (!empty($personid)) {
			if ($personid == 'self') {
				// Yes, it is PERSON->Getself
				$user = $this->checkpassword();
				$personid = $user;
			} else {
				$user = $this->checkpassword(FALSE);
			}
			$this->checktrafficlimit($user);

			// Reroute POST vars.
			$_POST['keywords'] = $this->input->get('name');
			$_POST['search'] = 'profiles';

			$user_model = new User_Model;

			$uid = $user_model->uid($personid);

			if ($uid == FALSE) {
				// User doesn't exist.
				echo $this->generatexml('failed',101,'person not found');
			} else {
				$user_information = $user_model->user_information($uid);

				$xml[0]['personid'] = $user_information['username'];
				if ($user_information['email_public'] == 1) {
					$xml[0]['email'] = $user_information['email'];
				} else {
					$xml[0]['email'] = '';
				}
				$xml[0]['gender'] = $user_information['gender'];
				$xml[0]['description'] = $user_information['description'];
				if (!empty($user_information['avatar'])) {
					$xml[0]['avatarpicfound'] = 1;
					$xml[0]['avatarpic'] = url::base() .'uploads/avatars/'. $user_information['avatar'] .'.jpg';
				} else {
					$xml[0]['avatarpicfound'] = 0;
					$xml[0]['avatarpic'] = '';
				}

				echo $this->generatexml('ok',100,'',$xml,'person','full',2);
			}
		// Otherwise it is PERSON->Search
		} else {
			// Reroute POST vars.
			$_POST['keywords'] = $this->input->get('name');
			$_POST['search'] = 'profiles';

			// Run our existing controller function.
			Site_Controller::search();

			$page = $this->input->get('page', 1);
			$pagesize = $this->input->get('pagesize', 50);

			if ($pagesize < 1 || $pagesize > 50) {
				$pagesize = 50;
			}

			$start = ($page - 1) * $pagesize;
			$end = $start + $pagesize;

			// Remap vars from original controller.
			$results = $this->template->content[0]->results;

			$usercount = count($results);

			$i = 0;
			foreach ($results as $row) {
				$i++;
				if ($i > $start && $i <= $end) {
					$xml[$i]['personid'] = $row->username;
					$xml[$i]['gender'] = $row->gender;
				}
			}

			echo $this->generatexml('ok',100,'',$xml,'person','summary',2,$usercount,$pagesize);
		}
	}

	public function activity_get() {
		$user = $this->checkpassword();
		$this->checktrafficlimit($user);

		$user_model = new User_Model;
		$update_model = new Update_Model;

		$uid = $user_model->uid($user);

		$page = $this->input->get('page', 1);
		$pagesize = $this->input->get('pagesize', 50);

		if ($pagesize < 1 || $pagesize > 50) {
			$pagesize = 50;
		}

		$start = ($page - 1) * $pagesize;
		$end = $pagesize;

		// Run our existing controller function.
		Dashboard_Controller::index($start);

		// Remap vars from original controller.
		$newsfeed = $this->template->content[1]->newsfeed;
		$totalitems = $this->template->content[1]->news_total;

		$i = 0;
		foreach ($newsfeed as $news) {
			$i++;
			if ($i <= $end) {
				$xml[$i]['personid'] = $news['user'];
				$xml[$i]['avatarpic'] = $news['avatar'];
				$xml[$i]['timestamp'] = $news['logtime'];
				if (strpos($news['text'], 'comment')) {
					$xml[$i]['type'] = 6;
				} elseif (strpos($news['text'], 'new update')) {
					$xml[$i]['type'] = 3;
				} elseif (strpos($news['text'], 'new project')) {
					$xml[$i]['type'] = 11;
				} elseif (strpos($news['text'], 'kudos')) {
					$xml[$i]['type'] = 9;
				} elseif (strpos($news['text'], 'subscribe')) {
					$xml[$i]['type'] = 12;
				} elseif (strpos($news['text'], 'track')) {
					$xml[$i]['type'] = 2;
				}
				$xml[$i]['message'] = $news['text'];
				$xml[$i]['link'] = $news['picture_url'];
			}
		}

		echo $this->generatexml('ok',100,'',$xml,'activity','full',2,$totalitems,$pagesize);
	}

	public function content_categories_get() {
		$user = $this->checkpassword(FALSE);
		$this->checktrafficlimit($user);

		$project_model = new Project_Model;
		$categories = $project_model->categories();

		$totalitems = count($categories);

		$i = 0;
		foreach ($categories as $id => $name) {
			$i++;
			$xml[$i]['id'] = $id;
			$xml[$i]['name'] = $name;
		}

		echo $this->generatexml('ok',100,'',$xml,'category','',2,$totalitems);
	}

	public function content_subcategories_get() {
		$personid = $this->uri->segment(5);

		if ($personid == 'self') {
			$user = $this->checkpassword();
			$personid = $user;
		} else {
			$user = $this->checkpassword(FALSE);
		}
		$this->checktrafficlimit($user);

		$project_model = new Project_Model;
		$user_model = new User_Model;

		$uid = $user_model->uid($personid);
		$projects = $project_model->projects($uid);
		$categories = $project_model->categories();

		$totalitems = count($projects);

		$i = 0;
		foreach ($projects as $id => $name) {
			$i++;
			$xml[$i]['id'] = $id;
			$xml[$i]['name'] = $name;

			$project_information = $project_model->project_information($id);
			$xml[$i]['category'] = $categories[$project_information['cid']];
			$xml[$i]['categoryid'] = $project_information['cid'];
			$project_personid = $user_model->user_information($project_information['uid']);
			$xml[$i]['personid'] = $project_personid['username'];
		}

		echo $this->generatexml('ok',100,'',$xml,'subcategory','',2,$totalitems);
	}

	public function content_data_get() {
		$user = $this->checkpassword(FALSE);
		$this->checktrafficlimit($user);

		$update_model = new Update_Model;
		$project_model = new Project_Model;
		$user_model = new User_Model;
		$kudos_model = new Kudos_Model;
		$comment_model = new Comment_Model;

		// Get our arguments
		$contentid = $this->uri->segment(5);
		$category = $this->input->get('categories');
		$subcategory = $this->input->get('subcategories');
		$user = $this->input->get('user');
		$search = explode(' ', $this->input->get('search'));

		$categories = $project_model->categories();

		$page = $this->input->get('page', NULL);

		// Workaround for the stupid first page is 0 thing
		if ($page == NULL) {
			$page = 1;
		} else {
			$page = $page + 1;
		}

		$pagesize = $this->input->get('pagesize', 50);

		if ($pagesize < 1 || $pagesize > 50) {
			$pagesize = 50;
		}

		$offset = ($page - 1) * $pagesize;

		$order = 'DESC';

		if (!empty($contentid)) {
			$update = $update_model->update_information($contentid);

			$xml[0]['id'] = $update['id'];
			$xml[0]['name'] = $update['summary'];
			$xml[0]['changed'] = $update['logtime'];
			$xml[0]['subcategoryid'] = $update['pid'];
			$subcategory = $project_model->project_information($update['pid']);
			$xml[0]['subcategory'] = $subcategory['name'];
			$xml[0]['categoryid'] = $subcategory['cid'];
			$xml[0]['category'] = $categories[$subcategory['cid']];
			$personid = $user_model->user_information($update['uid']);
			$xml[0]['personid'] = $personid['username'];
			$xml[0]['profilepage'] = url::base() .'profiles/view/'. $personid['username'] .'/';

			if ($update['ext0'] == 'jpg' || $update['ext0'] == 'gif' || $update['ext0'] == 'png') {
				$xml[0]['previewpic1'] = url::base() .'uploads/icons/'. $update['filename0'] .'_crop.jpg';
			}

			for ($i = 0; $i < 5; $i++) {
				if (!empty($update['ext'. $i])) {
					$xml[0]['smallpreviewpic'. ($i + 1)] = Updates_Controller::_file_icon($update['filename'. $i], $update['ext'. $i]);
					$xml[0]['downloadlink'. ($i + 1)] = url::base() .'uploads/files/'. $update['filename'. $i] .'.'. $update['ext'. $i];
					if ($update['ext'. $i] == 'jpg' || $update['ext'. $i] == 'png' || $update['ext'. $i] == 'gif') {
						$xml[0]['downloadtype'. ($i +1)] = 'image';
					} elseif ($update['ext'. $i] == 'avi' || $update['ext'. $i] == 'mpg' || $update['ext'. $i] == 'mov' || $update['ext'. $i] == 'flv' || $update['ext'. $i] == 'ogg' || $update['ext'. $i] == 'wmv') {
						$xml[0]['downloadtype'. ($i +1)] = 'video';
					} elseif ($update['ext'. $i] == 'mp3' || $update['ext'. $i] == 'wav') {
						$xml[0]['downloadtype'. ($i +1)] = 'sound';
					} else {
						$xml[0]['downloadtype'. ($i +1)] = 'download';
					}
				}
			}

			$xml[0]['score'] = $kudos_model->kudos($update['id']);
			$xml[0]['comments'] = $comment_model->comment_update_number($update['id']);

			echo $this->generatexml('ok',100,'',$xml,'content','full',2);
		} else {
			if (!empty($category)) {
				$updates = $update_model->updates('category', $category, $order, $pagesize, $offset);
				$totalitems = count($update_model->updates('category', $category));
			} elseif (!empty($subcategory)) {
				$uid = $project_model->project_information($subcategory);
				$uid = $uid['uid'];
				$updates = $update_model->updates($uid, $subcategory, $order, $pagesize, $offset);
				$totalitems = count($update_model->updates($uid, $subcategory));
			} elseif (!empty($user)) {
				$uid = $user_model->user_information($user);
				$uid = $uid['id'];
				$updates = $update_model->updates($uid, NULL, $order, $pagesize, $offset);
				$totalitems = count($update_model->updates($uid, NULL));
			} elseif (!empty($search)) {
				$updates = $update_model->search($search, 'updates');
				$totalitems = count($updates);
			}

			$i = 0;
			foreach ($updates as $update) {
				$i++;
				$xml[$i]['id'] = $update->id;
				$xml[$i]['name'] = $update->summary;
				$xml[$i]['changed'] = $update->logtime;
				$xml[$i]['subcategoryid'] = $update->pid;
				$subcategory = $project_model->project_information($update->pid);
				$xml[$i]['subcategory'] = $subcategory['name'];
				$xml[$i]['categoryid'] = $subcategory['cid'];
				$xml[$i]['category'] = $categories[$subcategory['cid']];
				$personid = $user_model->user_information($update->uid);
				$xml[$i]['personid'] = $personid['username'];
				$xml[$i]['profilepage'] = url::base() .'profiles/view/'. $personid['username'] .'/';

				if ($update->ext0 == 'jpg' || $update->ext0 == 'gif' || $update->ext0 == 'png') {
					$xml[$i]['previewpic1'] = url::base() .'uploads/icons/'. $update->filename0 .'_crop.jpg';
				}
				$xml[$i]['smallpreviewpic1'] = Updates_Controller::_file_icon($update->filename0, $update->ext0);

				$xml[$i]['score'] = $kudos_model->kudos($update->id);
				$xml[$i]['comments'] = $comment_model->comment_update_number($update->id);
			}

			echo $this->generatexml('ok',100,'',$xml,'content','summary',2,$totalitems);
		}
	}

	// Dirty hacks.

	public function _login_user($username, $password, $remember, $openid) {
		Users_Controller::_login_user($username, $password, $remember, $openid);
	}
}
