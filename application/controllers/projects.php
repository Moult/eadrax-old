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
 * @package		Project
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Users controller for tasks related to user management
 *
 * @category	Eadrax
 * @package		Project
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Projects_Controller extends Core_Controller {
	/**
	 * Process to add a new project.
	 *
	 * @return null
	 */
	public function add()
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$project_model = new Project_Model;

		if ($this->input->post())
		{
			$name			= $this->input->post('name');
			$website		= $this->input->post('website');
			$contributors	= $this->input->post('contributors');
			$description	= $this->input->post('description');

			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('name', 'required', 'length[1, 25]', 'standard_text');
			$validate->add_rules('website', 'url');
			$validate->add_rules('contributors', 'standard_text');
			$validate->add_rules('description', 'required');

			if ($validate->validate())
			{
				// Everything went great! Let's add the project.
				$project_model->manage_project(array(
					'uid'			=> $this->uid,
					'cid'			=> '1', # TODO until we create category support
					'name'			=> $name,
					'website'		=> $website,
					'contributors'	=> $contributors,
					'description'	=> $description
					));

				// Then load our success view.
				$project_success_view = new View('project_success');

				// Then generate content.
				$this->template->content = array($project_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$project_form_view = new View('project_form');
				$project_form_view->form = arr::overwrite(array(
					'name' => '',
					'website' => '',
					'contributors' => '',
					'description' => ''
					), $validate->as_array());
				$project_form_view->errors = $validate->errors('project_errors');

				// Generate the content.
				$this->template->content = array($project_form_view);
			}
		}
		else
		{
			// Load the neccessary view.
			$project_form_view = new View('project_form');

			// If we didn't press submit, we want a blank form.
			$project_form_view->form = array(
				'name' => '',
				'website' => '',
				'contributors' => '',
				'description' => ''
			);

			// Generate the content.
			$this->template->content = array($project_form_view);
		}

	}

}
