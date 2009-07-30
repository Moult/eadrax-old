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
	 * View a project.
	 *
	 * This will show the latest updates in a concise and paginated form 
	 * together with a project overview.
	 *
	 * @param int $pid The project ID to view.
	 *
	 * @return null
	 */
	public function view($pid)
	{
		// Load necessary models.
		$update_model	= new Update_Model;
		$project_model	= new Project_Model;

		// Let's update the project view statistics
		$project_model->view($pid);

		// TODO
	}

	/**
	 * Process to add/edit a project.
	 *
	 * @param int $pid If a project ID is specified, we will edit instead of 
	 * adding a project.
	 *
	 * @return null
	 */
	public function add($pid = FALSE)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$project_model = new Project_Model;

		// If a $pid is specified, this means we are editing a project, hence we 
		// need to perform some authentication checks.
		if ($pid != FALSE)
		{
			// Check whether or not we own the project.
			if (!$project_model->check_project_owner($pid, $this->uid))
			{
				die('You do not own this project.'); # TODO dying isn't very good.
			}
		}

		if ($this->input->post())
		{
			$name			= $this->input->post('name');
			$website		= $this->input->post('website');
			$contributors	= $this->input->post('contributors');
			$description	= $this->input->post('description');
			$cid			= $this->input->post('cid');

			if ($pid == FALSE)
			{
				// When adding a new project, there is no icon to start off with. If 
				// we detect a file for the icon later on, we will replace this.
				$icon_filename	= '';
			}
			else
			{
				$icon_filename = $project_model->project_information($pid);
				$icon_filename = $icon_filename['icon'];
			}

			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('name', 'required', 'length[1, 25]', 'standard_text');
			$validate->add_rules('website', 'url');
			$validate->add_rules('contributors', 'standard_text');
			$validate->add_rules('description', 'required');
			$validate->add_rules('cid', 'required', 'between[1, '. Kohana::config('projects.max_cid') .']');

			if ($validate->validate())
			{
				// First check whether or not we even have an icon to validate.
				if (!empty($_FILES) && !empty($_FILES['icon']['name']))
				{
					// Is there an existing icon?
					if (!empty($icon_filename))
					{
						// Delete the file.
						unlink(DOCROOT .'uploads/icons/'. $icon_filename);
					}

					// Do not forget we need to validate the file.
					$files = new Validation($_FILES);
					$files = $files->add_rules('icon', 'upload::valid', 'upload::type[jpg,png]', 'upload::size[1M]');

					if ($files->validate())
					{
						// Upload and resize the image.
						$filename = upload::save('icon');
						Image::factory($filename)->resize(80, 80, Image::WIDTH)->save(DOCROOT .'uploads/icons/'. basename($filename));
						unlink($filename);
						$icon_filename = basename($filename);
					}
					else
					{
						die ('Your upload has failed.');
					}
				}
				
				if ($pid == FALSE)
				{
					// Everything went great! Let's add the project.
					$project_model->manage_project(array(
						'uid'			=> $this->uid,
						'cid'			=> $cid,
						'name'			=> $name,
						'website'		=> $website,
						'contributors'	=> $contributors,
						'description'	=> $description,
						'icon'			=> $icon_filename
						));
				}
				else
				{
					// Everything went great! Let's edit the project.
					$project_model->manage_project(array(
						'uid'			=> $this->uid,
						'cid'			=> $cid,
						'name'			=> $name,
						'website'		=> $website,
						'contributors'	=> $contributors,
						'description'	=> $description,
						'icon'			=> $icon_filename
						), $pid);
				}

				if ($pid == FALSE)
				{
					// Then load our success view.
					$project_success_view = new View('project_success');
				}
				else
				{
					// Then load our success view.
					$project_success_view = new View('project_edit_success');
				}

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

				if ($pid != FALSE)
				{
					$project_form_view->pid = $pid;
				}

				// Set project categories.
				$project_form_view->categories = $project_model->categories();

				// Generate the content.
				$this->template->content = array($project_form_view);
			}
		}
		else
		{
			// Load the neccessary view.
			$project_form_view = new View('project_form');

			if ($pid == FALSE)
			{
				// If we didn't press submit, we want a blank form.
				$project_form_view->form = array(
					'name' => '',
					'website' => '',
					'contributors' => '',
					'description' => ''
				);
			}
			else
			{
				// If we didn't press submit, fill up the form with the existing 
				// info available in the database.
				$project_form_view->form = $project_model->project_information($pid);
				$project_form_view->pid = $pid;
			}

			// Set project categories.
			$project_form_view->categories = $project_model->categories();

			// Generate the content.
			$this->template->content = array($project_form_view);
		}

	}

	/**
	 * Deletes a project.
	 *
	 * @param int $pid The project ID of the project to delete.
	 *
	 * @return null
	 */
	public function delete($pid)
	{	
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$project_model = new Project_Model;

		// First check if you own the project.
		if (!empty($pid) && $project_model->check_project_owner($pid, $this->uid))
		{
			// Determine the value of the project's icon.
			$icon_filename	= $project_model->project_information($pid);
			$icon_filename	= $icon_filename['icon'];

			// Is there an existing image?
			if (!empty($icon_filename))
			{
				// Delete the file.
				unlink(DOCROOT .'uploads/icons/'. $icon_filename);
			}

			// Delete the project.
			$project_model->delete_project($pid);
		}
		else
		{
			die('Please ensure an ID is specified and you own the project.'); # TODO dying isn't good.
		}

		// Load views.
		$project_delete_view = new View('project_delete');

		// Generate the content.
		$this->template->content = array($project_delete_view);
	}

}
