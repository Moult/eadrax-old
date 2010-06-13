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
abstract class Openid_Controller extends Core_Controller {
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
			$store_path = DOCROOT ."application/tmp/_php_consumer_test";

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
			$consumer = new Auth_OpenID_Consumer($store);
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
			return sprintf("%s://%s:%s%s/finish_auth/",
						   getScheme(), $_SERVER['SERVER_NAME'],
						   $_SERVER['SERVER_PORT'],
						   dirname($_SERVER['PHP_SELF']));
		}

		function getReturnToLogin() {
			return sprintf("%s://%s:%s%s/finish_login/",
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
	 * Redirects to finish_auth if the user is registering with an OpenID. If 
	 * logging in with an OpenID, it will redirect to finish_login. If it fails 
	 * to authenticate the OpenID, depending on $login, it will display the 
	 * appropriate error page.
	 *
	 * @param bool $login TRUE if you are logging in, FALSE if registering.
	 *
	 * @return null
	 */
	public function try_auth($login)
	{
		function getOpenIDURL() {
			// Render a default page if we got a submission without an openid
			// value.
			if (empty($_POST['openid_identifier'])) {
				return FALSE;
			}

			return $_POST['openid_identifier'];
		}

		function run($login) {
			$openid = getOpenIDURL();
			$consumer = getConsumer();

			// Begin the OpenID authentication process.
			$auth_request = $consumer->begin($openid);

			// No auth request means we can't begin OpenID.
			if (!$auth_request) {
				return FALSE;
			}

			if ($login == TRUE)
			{
				// If we are just loggng in, we don't need information.
				$sreg_request = Auth_OpenID_SRegRequest::build();
			}
			elseif ($login == FALSE)
			{
				// If registering, we need a nickname to use.
				$sreg_request = Auth_OpenID_SRegRequest::build(
					// Required
					array('nickname')
					// Optional
					//array('fullname', 'email')
				);
			}

			if ($sreg_request) {
				$auth_request->addExtension($sreg_request);
			}

			if (isset($_POST['policies']))
			{
				$policy_uris = $_POST['policies'];
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
				if ($login == TRUE)
				{
					$redirect_url = $auth_request->redirectURL(getTrustRoot(), getReturnToLogin());
				}
				elseif ($login == FALSE)
				{
					$redirect_url = $auth_request->redirectURL(getTrustRoot(), getReturnTo());
				}

				// If the redirect URL can't be built, display an error
				// message.
				if (Auth_OpenID::isFailure($redirect_url)) {
					return FALSE;
				} else {
					// Send redirect.
					header("Location: ".$redirect_url);
				}
			} else {
				// Generate form markup and render it.
				$form_id = 'openid_message';
				if ($login == TRUE)
				{
					$form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnToLogin(), false, array('id' => $form_id));
				}
				else
				{
					$form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnTo(), false, array('id' => $form_id));
				}

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
		run($login);
	}

	/**
	 * Finish off the authentication process for registering.
	 *
	 * @return null
	 */
	public function finish_auth()
	{
		$this->_common();

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
			echo 'There was an error with your OpenID provider, please contact the administrator of this site to notify them of this problem, or register without OpenID.';
		} else if ($response->status == Auth_OpenID_FAILURE) {
			// Authentication failed; display the error message.
			$msg = "OpenID authentication failed: " . $response->message;
			echo 'There was an error with your OpenID provider, please contact the administrator of this site to notify them of this problem, or register without OpenID.';
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
			if (!isset($nickname))
			{
				// If not, then it should be blank.
				$nickname = '';
			}

			// If the OpenID was succesful, what about the nickname they 
			// provided? Note: $esc_identity = OpenID URL.
			$this->_validate($nickname, $esc_identity);
		}
	}

	/**
	 * Finish off the authentication process for logging in.
	 *
	 * @return null
	 */
	public function finish_login()
	{
		// Load necessary models.
		$openid_model = new Openid_Model;

		$this->_common();

		function escape($thing) {
			return htmlentities($thing);
		}

		$consumer = getConsumer();

		// Complete the authentication process using the server's
		// response.
		$return_to = getReturnToLogin();
		$response = $consumer->complete($return_to);

		// Check the response status.
		if ($response->status == Auth_OpenID_CANCEL) {
			// This means the authentication was cancelled.
			$msg = 'Verification cancelled.';
			echo 'There was an error with your OpenID provider, please contact the administrator of this site to notify them of this problem, or register without OpenID.';
		} else if ($response->status == Auth_OpenID_FAILURE) {
			// Authentication failed; display the error message.
			$msg = "OpenID authentication failed: " . $response->message;
			echo 'There was an error with your OpenID provider, please contact the administrator of this site to notify them of this problem, or register without OpenID.';
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

			$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			// Let's see if it's a registered OpenID.
			if (!$openid_model->unique_openid(array('openid_url'=>$esc_identity), FALSE))
			{
				// Yes, it _is_ an OpenID.
				// Let's change $username from the URL to the real username.
				$username = $openid_model->get_openid_username($esc_identity);
				$this->_login_user($username, FALSE, FALSE, TRUE);
			}
			else
			{
				// It is a proper OpenID, but we don't have it in our database.
				// Load the view.
				$login_view = new View('login');

				// There is an error!
				$login_view->error = 'The OpenID is not bound to a user account.';
				// Generate the content
				$this->template->content = array($login_view);
			}
		}
	}

	/**
	 * Function to validate OpenID nickname.
	 *
	 * This might seem like code reuse, but it actually isn't because in this 
	 * case the OpenID URL is preserved and reused in the form, and the 
	 * registration process also involves some binding.
	 *
	 * @param string $username	The nickname to validate.
	 * @param string $openid	The OpenID URL we are binding to.
	 *
	 * @return null
	 */
	public function _validate($username, $openid)
	{
		// Load necessary models.
		$user_model   = new User_Model;
		$openid_model = new Openid_Model;

		$validate = new Validation(array('openid_identifier' => $username, 'openid_url' => $openid));
		$validate->pre_filter('trim');
		$validate->add_rules('openid_identifier', 'required', 'length[5, 15]', 'alpha_dash');
		$validate->add_callbacks('openid_identifier', array($user_model, 'unique_user_name'));
		$validate->add_callbacks('openid_url', array($openid_model, 'unique_openid'));

		if ($validate->validate())
		{
			// Let's register the user!
			$openid_model->add_user($username, $openid);

			// Then load our success view.
			$register_success_view = new View('register_success');

			// Then generate content.
			$this->template->content = array($register_success_view);
		}
		else
		{
			// Errors have occured. Fill in the form and set errors.
			// Note that this time we fill in the "username" with the "openid"
			$register_view = new View('register');
			$register_view->form	= array('openid_identifier' => $openid);
			$register_view->errors	= $validate->errors('register_errors');

			// Generate the content.
			$this->template->content = array($register_view);
		}
	}

}
