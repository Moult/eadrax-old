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
	public function view($uid, $pid, $page = 1)
	{
		// Load necessary models.
		$update_model	= new Update_Model;
		$project_model	= new Project_Model;
		$kudos_model	= new Kudos_Model;
		$comment_model	= new Comment_Model;

		// Let's update the project view statistics
		$project_model->view($pid);

		$project_view = new View('project');

		// Parse the project itself first.
		$project_information = $project_model->project_information($pid);
		$project_view->project = $project_information;
		$project_view->categories = $project_model->categories();

		// Parse the description
		$description = $project_information['description'];

		$simple_search = array(
			'/\[b\](.*?)\[\/b\]/is',
			'/\[i\](.*?)\[\/i\]/is',
			'/\[u\](.*?)\[\/u\]/is',
			'/\[url\=(.*?)\](.*?)\[\/url\]/is',
			'/\[url\](.*?)\[\/url\]/is',
			'/\[list\](.*?)\[\/list\]/is',
			'/\[\*\](.*?)\[\/\*\]/is'
		);
		 
		$simple_replace = array(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<u>$1</u>',
			'<a href="$1" target="_blank">$2</a>',
			'<a href="$1" target="_blank">$1</a>',
			'<ul style="margin-left: 30px; font-size: 16px;">$1</ul>',
			'<li>$1</li>'
		);
		 
		$description = preg_replace($simple_search, $simple_replace, $description);

		$format = 'style="margin-bottom: 10px;"';
		$description = '<p '. $format .'>'. $description .'</p>';
		$description = preg_replace("/(?:\r?\n)+/", '</p><p '. $format .'>', $description);

		// Let's do some really nasty fixing to maintain HTML validity.
		$description = preg_replace(array('/<p '. $format .'><ul style="margin-left: 30px; font-size: 16px;"><\/p>/', '/<p '. $format .'><\/ul><\/p>/', '/<p '. $format .'><li>(.*?)<\/li><\/p>/'), array('<ul style="margin-left: 30px; font-size: 16px;">', '</ul>', '<li>$1</li>'), $description);

		$project_view->description = $description;

		// Let's parse individual updates.
		$query = $update_model->updates($uid, $pid, 'DESC', Kohana::config('projects.updates_page'), ($page-1)*Kohana::config('projects.updates_page'));
        $markup = '';

        if (count($query) > 0) {
            foreach ($query as $row) {
				$icon = Updates_Controller::_file_icon($row->filename0, $row->ext0, TRUE);
				$file_icon = '';
				if (strpos($icon, 'images/icons')) {
					$file_icon = $icon;
					$icon = url::base() .'images/noicon.png';
				}
                // Build the markup.
                $markup = $markup .'<div style="float: left; width: 260px; margin: 7px; height: 200px; border: 0px solid #F00;">';
				$markup = $markup .'<p><a href="'. url::base() .'/updates/view/'. $row->id .'/"><img style="vertical-align: middle; border: 1px solid #999; padding: 1px; background: url('. $icon .'); background-repeat: no-repeat; background-position: 1px 1px;" src="'. url::base() .'images/crop_overlay.png" alt="update icon" /></a></p>';
				$markup = $markup .'<cite style="background: #000000; -moz-opacity:.55; filter:alpha(opacity=55); opacity: .55; color: #FFF; position: relative; display: block; margin-left: auto; margin-right: auto; left: 2px; top: -64px; height: 30px; width: 240px; padding: 10px; border-top: 1px solid #888; font-weight: bold; word-wrap: break-word;"><span style="float: left; border: 0px solid #F00; height: 30px; width: 210px;">'. $row->summary .'</span><span style="font-weight: 100; font-size: 9px; float: right; position: relative; top: -2px; text-align: right;">'. $row->views .'V<br />'. $kudos_model->kudos($row->id) .'K<br />'. $comment_model->comment_update_number($row->id) .'C</span></cite>';
				if (!empty($file_icon)) {
					$markup = $markup .'<img src="'. $file_icon .'" style="position: relative; top: -170px; left: 205px;" />';
				}
                $markup = $markup .'</div>';
            }
        }

		$project_view->markup = $markup;
		$project_view->uid = $uid;

		// Pagination work.
		$project_view->pages = ceil(count($update_model->updates($uid, $pid)) / Kohana::config('projects.updates_page'));
		$project_view->page = $page;

		$this->template->content = array($project_view);

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
			$summary		= $this->input->post('summary');
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
			$validate->add_rules('summary', 'required', 'length[1,80]', 'standard_text');
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
						Image::factory($filename)->resize(50, 50, Image::AUTO)->save(DOCROOT .'uploads/icons/'. basename($filename));
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
						'summary'		=> $summary,
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
						'summary'		=> $summary,
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
					'summary' => '',
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
					'summary' => '',
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

	/**
	 * Generates the markup required for displaying a project timeline.
	 *
	 * @param int $pid The project ID
	 *
	 * @return string
	 */
	public function _generate_project_timeline($uid, $pid)
	{
		// Load necessary models.
		$update_model = new Update_Model;

		$query = $update_model->updates($uid, $pid, 'ASC', 18);
        $markup = '';

        if (count($query) > 0) {
            foreach ($query as $row) {
				$icon = Updates_Controller::_file_icon($row->filename0, $row->ext0);
                // Build the markup.
                $markup = '</div>'. $markup;
                $markup = '<h3><a href="'. url::base() .'/updates/view/'. $row->id .'/">'. $row->summary .'</a></h3><span>'. $row->logtime .'</span>'. $markup;
				if (!strpos($icon, 'images/icons')) { $markup_add = 'border: 1px solid #999; padding: 1px;'; } else { $markup_add = ''; }
				$markup = '<p><a href="'. url::base() .'/updates/view/'. $row->id .'/"><img style="vertical-align: middle; '. $markup_add .'" src="'. $icon .'" alt="update icon" /></a></p>'. $markup;
                $markup = '<div>'. $markup;
            }
        }
        return $markup;
	}

}
