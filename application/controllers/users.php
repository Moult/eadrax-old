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
 * @package		Core
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
	 * Set up routine.
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Process to register a user account.
	 *
	 * @return null
	 */
	public function register()
	{
		// Load necessary models.
		$user_model = new User_Model;

		// Load necessary view.
		$register_view = new View('register');

		$form = array(
			'username' => '',
			'password' => ''
		);

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$validate = Validation::factory($this->input->post())
			->pre_filter('trim')
			->add_rules('username', 'required', 'length[5, 15]', 'alpha_dash')
			->add_rules('password', 'required')
			->add_callbacks('username', array($user_model, 'unique_user_name'));

		if ($validate->validate())
		{
			$user_model->add_user($username, $password);
			echo 'You have been registered.';
		}
		else
		{
			echo 'Please recheck the form.';
			$form = arr::overwrite($form, $validate->as_array());
			$register_view->form = $form;
			foreach ($validate->errors('register_errors') as $key => $value)
			{
				echo '<br />'. $key .' says '. $value;
			}
		}

		$this->template->content = $register_view;

	}

	public function foo()
	{
	}

	/**
	 * Logs the user in and creates a user session.
	 *
	 * @return null
	 */
	public function login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if (empty($remember))
		{
			$remember = false;
		}

		if (Authlite::factory()->login($username, $password, $remember))
		{
			echo 'You have been logged in.';
		}
		else
		{
			echo 'You have failed to log in.';
		}
	}

	/**
	 * Logs the user out.
	 *
	 * @return null
	 */
	public function logout()
	{
		Authlite::factory()->logout();
	}
}
