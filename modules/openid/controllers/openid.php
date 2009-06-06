<?php
/**
 * OpenID module demo controller.
 *
 * $Id: openid_demo.php 2008-08-12 09:28:34 BST Atomless $
 *
 * This example controller should NOT be used in production,
 * it is for demonstration purposes only!
 *
 * @package    Openid
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Openid_Controller extends Template_Controller {

	// Do not allow to run in production
	const ALLOW_PRODUCTION = FALSE;

	public function __construct()
	{
		parent::__construct();
		// We need the OpenID library by Janrain to function.
		// I would prefer to move this into a subdirectory, but that would 
		// require some search-replace for require paths. This'll do for now.
		include('Auth/OpenID.php');
	}

	/**
	 * Currently does nothing.
	 */
	public function index()
	{
		$this->template->content =  array('please check out /register');
	}

	/**
	 * COMMON - included file.
	 */
	public function common()
	{
		$path_extra = dirname(dirname(dirname(__FILE__)));
		$path = ini_get('include_path');
		$path = $path_extra . PATH_SEPARATOR . $path;
		ini_set('include_path', $path);

		function displayError($message) {
			$error = $message;
			echo $error;
			exit(0);
		}

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
	 * FINISH AUTH - the final success message
	 */
	public function finish_auth()
	{
		$this->common();
		session_start();

		function escape($thing) {
			return htmlentities($thing);
		}

		function run() {
			$consumer = getConsumer();

			// Complete the authentication process using the server's
			// response.
			$return_to = getReturnTo();
			$response = $consumer->complete($return_to);

			// Check the response status.
			if ($response->status == Auth_OpenID_CANCEL) {
				// This means the authentication was cancelled.
				$msg = 'Verification cancelled.';
			} else if ($response->status == Auth_OpenID_FAILURE) {
				// Authentication failed; display the error message.
				$msg = "OpenID authentication failed: " . $response->message;
			} else if ($response->status == Auth_OpenID_SUCCESS) {
				// This means the authentication succeeded; extract the
				// identity URL and Simple Registration data (if it was
				// returned).
				$openid = $response->getDisplayIdentifier();
				$esc_identity = escape($openid);

				$success = sprintf('You have successfully verified ' .
								   '<a href="%s">%s</a> as your identity.',
								   $esc_identity, $esc_identity);

				if ($response->endpoint->canonicalID) {
					$escaped_canonicalID = escape($response->endpoint->canonicalID);
					$success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
				}

				$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

				$sreg = $sreg_resp->contents();

				if (@$sreg['email']) {
					$success .= "  You also returned '".escape($sreg['email']).
						"' as your email.";
				}

				if (@$sreg['nickname']) {
					$success .= "  Your nickname is '".escape($sreg['nickname']).
						"'.";
				}

				if (@$sreg['fullname']) {
					$success .= "  Your fullname is '".escape($sreg['fullname']).
						"'.";
				}

			$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			if ($pape_resp) {
					if ($pape_resp->auth_policies) {
						$success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

						foreach ($pape_resp->auth_policies as $uri) {
							$escaped_uri = escape($uri);
							$success .= "<li><tt>$escaped_uri</tt></li>";
						}

						$success .= "</ul>";
					} else {
						$success .= "<p>No PAPE policies affected the authentication.</p>";
					}

					if ($pape_resp->auth_age) {
						$age = escape($pape_resp->auth_age);
						$success .= "<p>The authentication age returned by the " .
							"server is: <tt>".$age."</tt></p>";
					}

					if ($pape_resp->nist_auth_level) {
						$auth_level = escape($pape_resp->nist_auth_level);
						$success .= "<p>The NIST auth level returned by the " .
							"server is: <tt>".$auth_level."</tt></p>";
					}

			} else {
					$success .= "<p>No PAPE response was sent by the provider.</p>";
			}
			}

			echo 'including index results: <br />'. $success;
		}

		run();

		$this->template->content = array();
	}

	/**
	 * TRY AUTH - the thing register submits to
	 */
	public function try_auth()
	{
		$this->common();

		session_start();

		$this->run();

	}

	function getOpenIDURL() {
		// Render a default page if we got a submission without an openid
		// value.
		if (empty($_GET['openid_identifier'])) {
			$error = "Expected an OpenID URL.";
			echo $error;
			exit(0);
		}

		return $_GET['openid_identifier'];
	}

	function run() {
		$openid = $this->getOpenIDURL();
		$consumer = getConsumer();

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($openid);

		// No auth request means we can't begin OpenID.
		if (!$auth_request) {
			displayError("Authentication error; not a valid OpenID.");
		}

		$sreg_request = Auth_OpenID_SRegRequest::build(
										 // Required
										 array('nickname'),
										 // Optional
										 array('fullname', 'email'));

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
				displayError("Could not redirect to server: " . $redirect_url->message);
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
		$this->template->content = '';
	}

	/**
	 * Example openID user registration page
	 */
	public function register()
	{
		$this->common();
		?>

<?php

global $pape_policy_uris;
?>


    <h1>PHP OpenID Authentication Example</h1>
    <p>
      This example consumer uses the <a
      href="http://www.openidenabled.com/openid/libraries/php/">PHP
      OpenID</a> library. It just verifies that the URL that you enter
      is your identity URL.
    </p>

    <?php if (isset($msg)) { print "<div class=\"alert\">$msg</div>"; } ?>
    <?php if (isset($error)) { print "<div class=\"error\">$error</div>"; } ?>
    <?php if (isset($success)) { print "<div class=\"success\">$success</div>"; } ?>

    <div id="verify-form">
      <form method="get" action="try_auth">
        Identity&nbsp;URL:
        <input type="hidden" name="action" value="verify" />
        <input type="text" name="openid_identifier" value="" />

        <p>Optionally, request these PAPE policies:</p>
        <p>
        <?php foreach ($pape_policy_uris as $i => $uri) {
          print "<input type=\"checkbox\" name=\"policies[]\" value=\"$uri\" />";
          print "$uri<br/>";
        } ?>
        </p>

        <input type="submit" value="Verify" />
      </form>
    </div>


<?php

			$this->template->content = array();
	}

} // End Auth Controller
