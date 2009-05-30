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
	 * Process to register a user account.
	 *
	 * @return null
	 */
	public function register()
	{
		// Load necessary models.
		$user_model = new User_Model;

		if ($this->input->post())
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('username', 'required', 'length[5, 15]', 'alpha_dash');
			$validate->add_rules('password', 'required');
			$validate->add_callbacks('username', array($user_model, 'unique_user_name'));

			if ($validate->validate())
			{
				// Everything went great! Let's register.
				$user_model->add_user($username, $password);

				// Then load our success view.
				$register_success_view = new View('register_success');

				// Then generate content.
				$this->template->content = array($register_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$register_view = new View('register');
				$register_view->form	= arr::overwrite(array(
					'username' => '',
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
			$register_view->form = array('username'=>'');

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
		if ($this->input->post())
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if (empty($remember))
			{
				$remember = false;
			}

			$authlite = new Authlite();
			if ($authlite->login($username, $password, $remember))
			{
				// Load the view.
				$login_view = new View('login_success');

				// Generate the content.
				$this->template->content = array($login_view);
			}
			else
			{
				// Load the view.
				$login_view = new View('login');

				// There is an error!
				$login_view->error = 'You have failed to log in';
				// Generate the content
				$this->template->content = array($login_view);
			}
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
	 * Logs the user out.
	 *
	 * @return null
	 */
	public function logout()
	{
		$authlite = new Authlite();
		$authlite->logout();
	}
}
