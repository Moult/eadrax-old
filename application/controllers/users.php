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
 * Users controller for tasks related to user management
 *
 * @category	Eadrax
 * @package		User
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Users_Controller extends Core_Controller {
	/**
	 * Talks to the Janrain OpenID service to allow OpenID registrations.
	 *
	 * @return null
	 */
	public function rpx()
	{
		$rpxApiKey = Kohana::config('authlite.rpxApiKey');

		if(isset($_POST['token'])) { 

			// STEP 1: Extract token POST parameter
			$token = $_POST['token'];

			// STEP 2: Use the token to make the auth_info API call
			$post_data = array('token' => $_POST['token'],
							 'apiKey' => $rpxApiKey,
							 'format' => 'json'); 

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$raw_json = curl_exec($curl);
			curl_close($curl);

			// STEP 3: Parse the JSON auth_info response
			$auth_info = json_decode($raw_json, true);

			if ($auth_info['stat'] == 'ok') {
			  
				// STEP 3 Continued: Extract the 'identifier' from the response
				$profile = $auth_info['profile'];
				$identifier = $profile['identifier'];
				$openid = $identifier;

				if (isset($profile['preferredUsername']))  {
				  $username = $profile['preferredUsername'];
				}

				// STEP 4: Use the identifier as the unique key to sign the user into your system.

				// Load necessary models.
				$openid_model = new Openid_Model;

				// Let's see if it's a registered OpenID.
				if (!$openid_model->unique_openid(array('openid_url' => $openid), FALSE)) {
					// Let's log them in without bothering thme.
					$username = $openid_model->get_openid_username($openid);
					$this->_login_user($username, FALSE, FALSE, TRUE);
				} else {
					// ... if not, let's register them.
					// Load necessary models.
					$user_model   = new User_Model;

					$validate = new Validation(array('openid_identifier' => $username, 'openid_url' => $openid));
					$validate->pre_filter('trim');
					$validate->add_rules('openid_identifier', 'required', 'length[5, 15]', 'alpha_dash');
					$validate->add_callbacks('openid_identifier', array($user_model, 'unique_user_name'));
					$validate->add_callbacks('openid_url', array($openid_model, 'unique_openid'));

					if ($validate->validate())
					{
						// Grab profile information from the OpenID provider.
						$data = array();

						if (isset($profile['email'])) {
						  $data['email'] = $profile['email'];
						}

						if (isset($profile['gender'])) {
						  $data['gender'] = ucfirst(strtolower(trim($profile['gender'])));
						}

						if (isset($profile['url'])) {
						  $data['website'] = format::url($profile['url']);
						}

						// Let's register the user!
						$uid = $openid_model->add_user($username, $openid);

						// Update the user with any possible information.
						$user_model->manage_user($data, $uid);

						// ... and immediately log them in.
						$this->_login_user($username, FALSE, FALSE, TRUE);
					} else {
						// Errors have occured. Fill in the form and set errors.
						// Note that this time we fill in the "username" with the "openid"
						$register_view = new View('register');
						$register_view->form	= array('openid_identifier' => $username);
						$register_view->errors	= $validate->errors('register_errors');

						// Generate the content.
						$this->template->content = array($register_view);
					}
				}
			} else {
				// Error occured - gracefully handle the error by redirecting to the login page.
				$this->session->set('notification', 'Uh oh! An OpenID error occured: '. $auth_info['err']['msg'] .' - please try again.');
				url::redirect(url::base() .'users/login/');
			}
		}
	}

	/**
	 * Process to register a user account.
	 *
	 * @return null
	 */
	public function register()
	{
		// We only want non logged in people.
		$this->restrict_access(TRUE);

		// Load necessary models.
		$user_model = new User_Model;

		if ($this->input->post())
		{
			$username = $this->input->post('openid_identifier');
			$password = $this->input->post('password');

			// ...and we continue doing normal registration.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('openid_identifier', 'required', 'length[5, 15]', 'alpha_dash');
			$validate->add_rules('password', 'required');
			$validate->add_callbacks('openid_identifier', array($user_model, 'unique_user_name'));

			if ($validate->validate())
			{
				// Everything went great! Let's register.
				$user_model->add_user($username, $password);

				// Log them in automatically.
				$this->_login_user($username, $password, FALSE, FALSE);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$register_view = new View('register');
				$register_view->form	= arr::overwrite(array(
					'openid_identifier' => '',
					'password' => ''
					), $validate->as_array());
				$register_view->errors	= $validate->errors('register_errors');

				// Generate the content.
				$this->template->content = array($register_view);
			}
		}
		else
		{
			// Load the neccessary view.
			$register_view = new View('register');

			// If we didn't press submit, we want a blank form.
			$register_view->form = array('openid_identifier'=>'');

			// Generate the content.
			$this->template->content = array($register_view);
		}
	}

	/**
	 * Logs the user in and creates a user session.
	 *
	 * @return null
	 */
	public function login()
	{
		$this->restrict_access(TRUE);

		if ($this->input->post())
		{
			$username = $this->input->post('openid_identifier');
			$password = $this->input->post('password');
			$remember = $this->input->post('remember');

			if ($remember == 'on') {
				$remember = TRUE;
			} else {
				$remember = FALSE;
			}

			// Do normal login.
			$this->_login_user($username, $password, $remember, FALSE);
		}
		else
		{
			// Load the view.
			$login_view = new View('login');

			// Generate the content
			$this->template->content = array($login_view);
		}
	}

	/**
	 * Logs the user into the system.
	 *
	 * @param string $username	The username of the user to log in.
	 * @param string $password	The password of the user. Option for OpenID.
	 * @param string $remember	Whether or not to remember the login.
	 * @param bool	 $openid	TRUE if we are logging in via OpenID else FALSE.
	 *
	 * @return null
	 */
	public function _login_user($username, $password, $remember, $openid)
	{
		if ($openid == TRUE)
		{
			// When logging in via OpenID, we don't need $password. So we force 
			// the user to log in.
			$authlite = new Authlite();
			if ($authlite->force_login($username))
			{
				// Set login variables manually.
				$this->username		= $this->authlite->get_user()->username;
				$this->uid			= $this->authlite->get_user()->id;
				$this->logged_in	= TRUE;

				// Redirect to the dashboard.
				$this->session->set('notification', 'Welcome back, '. $this->username .'. We really, really missed you. Seriously.');
				url::redirect(url::base() .'dashboard/');
			}
		}
		elseif ($openid == FALSE)
		{
			$authlite = new Authlite();
			if ($authlite->login($username, $password, $remember))
			{
				// Set login variables manually.
				$this->username		= $this->authlite->get_user()->username;
				$this->uid			= $this->authlite->get_user()->id;
				$this->logged_in	= TRUE;

				// Redirect to the dashboard.
				$this->session->set('notification', 'Welcome back, '. $this->username .'. We really, really missed you. Seriously.');
				url::redirect(url::base() .'updates/add/');
			} else {
				// The account doesn't exist, let's register them instead.
				$this->register();
			}
		}
	}

	/**
	 * Logs the user out.
	 *
	 * @return null
	 */
	public function logout()
	{
		if($this->authlite->logout()){
			// Redirect to the project itself.
			$this->session->set('notification', 'Goodbye :( Please come back soon. Pretty please?');
			url::redirect(url::base());
		}
	}
}
