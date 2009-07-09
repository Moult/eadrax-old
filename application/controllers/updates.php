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
 * @package		Update
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Updates controller added for update system.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Updates_Controller extends Core_Controller {
	/**
	 * Adds a new update.
	 *
	 * @return null
	 */
	public function index()
	{
		// Logged in users and guest users will have different abilities when 
		// submitting updates to the website.
		if ($this->logged_in == TRUE)
		{
			// If the person is logged in...make sure they really are.
			$this->restrict_access();

			// Load necessary models.
			$update_model	= new Update_Model;
			$project_model	= new Project_Model;

			if ($this->input->post())
			{
				$summary	= $this->input->post('summary');
				$detail		= $this->input->post('detail');
				$pid		= $this->input->post('pid');

				// Begin to validate the information.
				$validate = new Validation($this->input->post());
				$validate->pre_filter('trim');
				$validate->add_rules('summary', 'required', 'length[5, 70]', 'standard_text');
				$validate->add_rules('detail', 'standard_text');
				$validate->add_rules('pid', 'required', 'digit');
				$validate->add_callbacks('pid', array($this, '_validate_project_owner'));

				if ($validate->validate())
				{
					// Everything went great! Let's add the update.
					$update_model->manage_update(array(
						'uid' => $this->uid,
						'summary' => $summary,
						'detail' => $detail,
						'pid' => $pid
					));

					// Then load our success view.
					$update_success_view = new View('update_success');

					// Then generate content.
					$this->template->content = array($update_success_view);
				}
				else
				{
					// Errors have occured. Fill in the form and set errors.
					$update_form_view = new View('update_form');
					$update_form_view->form = arr::overwrite(array(
						'summary' => '',
						'detail' => ''
					), $validate->as_array());
					$update_form_view->errors = $validate->errors('update_errors');

					// Set list of projects.
					$update_form_view->projects = $project_model->projects($this->uid);

					// Generate the content.
					$this->template->content = array($update_form_view);
				}
				// TODO
			}
			else
			{
				// Load the necessary view.
				$update_form_view = new View('update_form');

				// If we didn't press submit, we want a blank form.
				$update_form_view->form = array(
					'summary' => '',
					'detail' => ''
				);

				// Set list of projects.
				$update_form_view->projects = $project_model->projects($this->uid);

				// Generate the content.
				$this->template->content = array($update_form_view);
			}
		}
		else
		{
			// The person is a guest...
			// TODO
		}
	}

	/**
	 * Validates that the owner of a project is the logged in user.
	 *
	 */
	public function _validate_project_owner(Validation $array, $field)
	{
		$project_model = new Project_Model;

		$project_uid = $project_model->project_information($array[$field]);
		$project_uid = $project_uid['uid'];

		// We also allow the project uid to be 1, as this is a project owned by 
		// a guest - used for special universal projects.
		if ($project_uid != $this->uid && $project_uid != 1)
		{
			$array->add_error($field, 'project_owner');
		}
	}
}
