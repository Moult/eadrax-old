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
class Users_Controller extends Openid_Controller {
	/**
	 * Process to register a user account.
	 *
	 * @return null
	 */
	public function register()
	{
		// We only want logged in people.
		$this->restrict_access(TRUE);

		// Load necessary models.
		$user_model = new User_Model;

		if ($this->input->post())
		{
			$username = $this->input->post('openid_identifier');
			$password = $this->input->post('password');

			// This line runs the OpenID check. If the OpenID check succeeds, it 
			// will automatically continue with the OpenID registration system. 
			// If it fails (hence the user is not using OpenID), it will return 
			// false and continue with normal registration.
			// Note: The OpenID can also fail in the _later_ part of 
			// authentication, after it has redirected to a completely different 
			// method in the OpenID controller. It currently does _not_ fall 
			// back to normal registration if this happens.
			$this->try_auth(FALSE);

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

				// Then load our success view.
				$register_success_view = new View('register_success');
				$register_introduction_view = new View('register_introduction');

				// Then generate content.
				$this->template->content = array($register_success_view, $register_introduction_view);
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

			// Run the OpenID authentication. If it fails, it'll continue with 
			// normal user login. If not, it'll continue the registration in 
			// Openid_Controller->finish_login();
			$this->try_auth(TRUE);

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

				// Load the view.
				$login_view = new View('login_success');

				// Generate the content.
				$this->template->content = array($login_view);
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

				// Load the view.
				$login_success_view = new View('login_success');

				// Give it some basic variables.
				$login_success_view->username = $username;

				// Generate the content.
				$this->template->content = array($login_success_view);
			}
			else
			{
				// Load the view.
				$login_view = new View('login');

				// There is an error!
				$login_view->errors = array('openid_identifier' => 'Your account details do not match');

				// Generate the content
				$this->template->content = array($login_view);
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
			url::redirect();
		}
	}
}
