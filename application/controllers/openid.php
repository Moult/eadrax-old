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
 * @package		User
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * OpenID controller to authenticate OpenID registrations.
 *
 * @category	Eadrax
 * @package		User
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Openid_Controller extends Core_Controller {
	/**
	 * We need the Auth libraries by Janrain to function.
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct();
		include('Auth/OpenID.php');
	}

	/**
	 * Commonly needed functions for OpenID to work.
	 *
	 * @return null
	 */
	public function _common()
	{
		$path_extra = dirname(dirname(dirname(__FILE__)));
		$path = ini_get('include_path');
		$path = $path_extra . PATH_SEPARATOR . $path;
		ini_set('include_path', $path);

		function doIncludes() {
			/**
			 * Require the OpenID consumer code.
			 */
			require_once "Auth/OpenID/Consumer.php";

			/**
			 * Require the "file store" module, which we'll need to store
			 * OpenID information.
			 */
			require_once "Auth/OpenID/FileStore.php";

			/**
			 * Require the Simple Registration extension API.
			 */
			require_once "Auth/OpenID/SReg.php";

			/**
			 * Require the PAPE extension module.
			 */
			require_once "Auth/OpenID/PAPE.php";
		}

		doIncludes();

		global $pape_policy_uris;
		$pape_policy_uris = array(
					  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
					  PAPE_AUTH_MULTI_FACTOR,
					  PAPE_AUTH_PHISHING_RESISTANT
					  );

		function &getStore() {
			/**
			 * This is where the example will store its OpenID information.
			 * You should change this path if you want the example store to be
			 * created elsewhere.  After you're done playing with the example
			 * script, you'll have to remove this directory manually.
			 */
			$store_path = "/tmp/_php_consumer_test";

			if (!file_exists($store_path) &&
				!mkdir($store_path)) {
				print "Could not create the FileStore directory '$store_path'. ".
					" Please check the effective permissions.";
				exit(0);
			}

			$var = new Auth_OpenID_FileStore($store_path);
			return $var;
		}

		function &getConsumer() {
			/**
			 * Create a consumer object using the store object created
			 * earlier.
			 */
			$store = getStore();
			$consumer =& new Auth_OpenID_Consumer($store);
			return $consumer;
		}

		function getScheme() {
			$scheme = 'http';
			if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
				$scheme .= 's';
			}
			return $scheme;
		}

		function getReturnTo() {
			return sprintf("%s://%s:%s%s/finish_auth",
						   getScheme(), $_SERVER['SERVER_NAME'],
						   $_SERVER['SERVER_PORT'],
						   dirname($_SERVER['PHP_SELF']));
		}

		function getTrustRoot() {
			return sprintf("%s://%s:%s%s/",
						   getScheme(), $_SERVER['SERVER_NAME'],
						   $_SERVER['SERVER_PORT'],
						   dirname($_SERVER['PHP_SELF']));
		}
	}

	/**
	 * Authenticates the OpenID.
	 *
	 * Returns True if else false.
	 *
	 * @return bool
	 */
	public function try_auth()
	{
		function getOpenIDURL() {
			// Render a default page if we got a submission without an openid
			// value.
			if (empty($_GET['openid_identifier'])) {
				$error = "Expected an OpenID URL.";
				echo 'OpenID borked up. Go to normal registration now.';
				return FALSE;
			}

			return $_GET['openid_identifier'];
		}

		function run() {
			$openid = getOpenIDURL();
			$consumer = getConsumer();

			// Begin the OpenID authentication process.
			$auth_request = $consumer->begin($openid);

			// No auth request means we can't begin OpenID.
			if (!$auth_request) {
				// displayError("Authentication error; not a valid OpenID.");
				echo 'OpenID borked up. Go to normal registration now.';
				return FALSE;
			}

			$sreg_request = Auth_OpenID_SRegRequest::build(
											 // Required
											 array('nickname')
											 // Optional
											 //array('fullname', 'email')
											 );

			if ($sreg_request) {
				$auth_request->addExtension($sreg_request);
			}

			if (isset($_GET['policies']))
			{
				$policy_uris = $_GET['policies'];
			}
			else
			{
				$policy_uris = array();
			}

			$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
			if ($pape_request) {
				$auth_request->addExtension($pape_request);
			}

			// Redirect the user to the OpenID server for authentication.
			// Store the token for this authentication so we can verify the
			// response.

			// For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
			// form to send a POST request to the server.
			if ($auth_request->shouldSendRedirect()) {
				$redirect_url = $auth_request->redirectURL(getTrustRoot(),
														   getReturnTo());

				// If the redirect URL can't be built, display an error
				// message.
				if (Auth_OpenID::isFailure($redirect_url)) {
					// displayError("Could not redirect to server: " . $redirect_url->message);
					return FALSE;
				} else {
					// Send redirect.
					header("Location: ".$redirect_url);
				}
			} else {
				// Generate form markup and render it.
				$form_id = 'openid_message';
				$form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnTo(),
													   false, array('id' => $form_id));

				// Display an error if the form markup couldn't be generated;
				// otherwise, render the HTML.
				if (Auth_OpenID::isFailure($form_html)) {
					displayError("Could not redirect to server: " . $form_html->message);
				} else {
					print $form_html;
				}
			}
		}

		// After defining the functions we need, let's run them.
		$this->_common();
		session_start();
		run();
	}

	/**
	 * Finish off the authentication process.
	 *
	 * @return null
	 */
	public function finish_auth()
	{
		$this->_common();
		session_start();

		function escape($thing) {
			return htmlentities($thing);
		}

		$consumer = getConsumer();

		// Complete the authentication process using the server's
		// response.
		$return_to = getReturnTo();
		$response = $consumer->complete($return_to);

		// Check the response status.
		if ($response->status == Auth_OpenID_CANCEL) {
			// This means the authentication was cancelled.
			$msg = 'Verification cancelled.';
			echo 'OpenID borked up. Go to normal registration now.';
		} else if ($response->status == Auth_OpenID_FAILURE) {
			// Authentication failed; display the error message.
			$msg = "OpenID authentication failed: " . $response->message;
			echo 'OpenID borked up. Go to normal registration now.';
		} else if ($response->status == Auth_OpenID_SUCCESS) {
			// This means the authentication succeeded; extract the
			// identity URL and Simple Registration data (if it was
			// returned).
			$openid = $response->getDisplayIdentifier();
			$esc_identity = escape($openid);

			if ($response->endpoint->canonicalID) {
				$escaped_canonicalID = escape($response->endpoint->canonicalID);
				$success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
			}

			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

			$sreg = $sreg_resp->contents();

			if (@$sreg['nickname']) {
				$nickname = escape($sreg['nickname']);
			}

			$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			// Do they have a nickname?
			if (isset($nickname))
			{
				// OpenID succesfull.
				$this->template->content = array('We will log your url as '. $esc_identity .' and bind the openid to an auto generated user with the username '. $nickname .' with a random password.');
			}
			else
			{
				// They NEED a nickname.
			}

			// We're DONE here.
		}
	}

	/**
	 * Testing registration page
	 *
	 * @return null
	 */
	public function register()
	{
		$this->_common();
		$openid = new View('openid');
		$this->template->content = array($openid);
	}

}
