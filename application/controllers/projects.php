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
	 * @param int $uid  The user ID to view.
	 * @param int $pid  The project ID to view.
	 * @param int $page Which page we are on.
	 *
	 * @return null
	 */
	public function view($uid = NULL, $pid = NULL, $page = 1)
	{
		// Load necessary models.
		$update_model	= new Update_Model;
		$project_model	= new Project_Model;
		$kudos_model	= new Kudos_Model;
		$comment_model	= new Comment_Model;
		$user_model		= new User_Model;

		$this->template->content = array();

		// This is the view in which project updates are shown.
		$project_view = new View('project');

		// Reset special uid options for sorting results.
		if ($uid == 'a') {
			$project_view->filter = $uid;
			$uid = 0;
			$order = array('views', 'DESC');
		} elseif ($uid == 'r') {
			$project_view->filter = $uid;
			$uid = 0;
			$order = array(NULL, 'RAND()');
		} else {
			$project_view->filter = 'l';
			$order = 'DESC';
		}

		// Reset variables if a 0 has been given.
		if ($uid == '0') {
			$uid = NULL;
		}

		// Check for potential 404s.
		if ($uid != NULL && $uid != 'category') {
			if (!$user_model->check_user_exists($uid)) {
				Event::run('system.404');
			}
		}
		if ($pid != NULL) {
			if (!$project_model->check_project_exists($pid) && $pid != 0 && $uid != 'category') {
				Event::run('system.404');
			} elseif ($uid == 'category' && ($pid < 1 || $pid > Kohana::config('projects.max_cid'))) {
				Event::run('system.404');
			}
		}

		if ($pid == 0) {
			// If there is a user given, but no project...
			if ($uid != NULL) {
				// Then we are viewing their profile.

				// Load the main profile view.
				$profile_view = new View('profile');
				$user_information = $user_model->user_information($uid);
				$profile_view->user = $user_information;

				// Calculate age from date of birth.
				if(!empty($user_information['dob']))
				{
					list($dd, $mm, $yyyy) = explode('/', $user_information['dob']);
					$age = date('Y') - $yyyy;

					if(date('m') < $mm || (date('m') == $mm && date('d') < $dd)) {
						$age--;
					}

					if ($age == 2010) {
						$age = 'no';
					}
				}
				else
				{
					$age = '';
				}

				$profile_view->age = $age;
				$profile_view->uid = $uid;

				$this->template->content[] = $profile_view;

				$project_view->join = 1;
				$this->template->join = 1;
			}

			$pid = NULL;
		}

		// Let's update the project view statistics
		$project_model->view($pid);

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
			'<ul>$1</ul>',
			'<li>$1</li>'
		);
		 
		$description = preg_replace($simple_search, $simple_replace, $description);

		$description = text::auto_link($description);
		$description = text::auto_p($description);

		$project_view->description = $description;

		// Parse the contributors.
		$contributors = $project_information['contributors'];
		if (!empty($contributors)) {
			$contributors = explode(',', $contributors);
			foreach ($contributors as $con_id => $contributor) {
				preg_match('/\(.*\)/', $contributor, $matches);
				if (!empty($matches[0])) {
					// We've got a possible alias! Strip it out!
					$contributor = str_replace($matches[0], '', $contributor);
					$contributors[$con_id] = trim($contributor);

					// No brackets please...
					$alias_name = substr($matches[0], 1, -1);
					$aliases[] = $alias_name;

					// Does this alias match a user?
					if ($user_model->uid($alias_name)) {
						$match[] = $user_model->uid($alias_name);
					} else {
						$match[] = FALSE;
					}
				} else {
					// No alias.
					$aliases[] = FALSE;
					$match[] = FALSE;
				}
			}
			$project_view->contributors = $contributors;
			$project_view->aliases = $aliases;
			$project_view->match = $match;
		} else {
			$contributors = FALSE;
		}

		if ($pid != 0) {
			// Calculate average view per updates and average kudos per update.
			$project_updates = $update_model->project_updates($pid, $uid);
			$total_views = $update_model->view_number_time($uid, 0, "2020-01-01 01:01:01", $pid);
			$total_kudos = $kudos_model->kudos_project($pid, $uid);

			if ($project_updates == 0) {
				$average_views = 0;
				$average_kudos = 0;
			} else {
				$average_views = $total_views / $project_updates;
				$average_kudos = $total_kudos / $project_updates;
			}
		}

		// Let's parse individual updates.
		if ($uid != 'category') {
			$query = $update_model->updates($uid, $pid, $order, Kohana::config('projects.updates_page'), ($page-1)*Kohana::config('projects.updates_page'));
		} else {
			$query = $update_model->updates($uid, $pid, $order, Kohana::config('projects.updates_page'), ($page-1)*Kohana::config('projects.updates_page'));
			$category_name = $project_model->categories();
			$project_view->category_name = $category_name[$pid];
			$project_view->category_id = $pid;
		}
        $markup = '';

        if (count($query) > 0) {
            foreach ($query as $row) {
				$icon = Updates_Controller::_file_icon($row->filename0, $row->ext0, TRUE);
				$file_icon = '';
				if (strpos($icon, 'images/icons')) {
					$file_icon = $icon;
					$icon = url::base() .'images/noicon.png';
				}

				$project_name = $project_model->project_information($row->pid);
				$project_name = $project_name['name'];
				$star_width = 0;

				if ($pid != 0 && $uid != 'category') {
					$project_name = '';

					// Calculate star width
					if ($average_kudos == 0) {
						$star_width_kudos = $kudos_model->kudos($row->id) * 13;
					} else {
						$star_width_kudos = (($kudos_model->kudos($row->id) / $average_kudos) - 1) * 130;
					}

					if ($star_width_kudos < 0) {
						$star_width_kudos = 0;
					}

					if ($average_views == 0) {
						$star_width_views = $row->views * 13;
					} else {
						$star_width_views = (($row->views / $average_views) - 1) * 130;
					}

					if ($star_width_views < 0) {
						$star_width_views = 0;
					}

					$star_width = $star_width_kudos + $star_width_views;
					if ($star_width > 130) {
						$star_width = 130;
					}
				}

				if (date('Y') == date('Y', strtotime($row->logtime))) {
					$datestring = 'j M';
				} else {
					$datestring = 'j M Y';
				}

                // Build the markup.
                $markup = $markup .'<div style="float: left; width: 260px; height: 240px; border: 0px solid #F00; margin: 7px;">';
				$markup = $markup .'<div style="height: 20px; width: 262px; margin-bottom: 5px; background-color: #1c1b19; background-repeat: repeat-x; background-image: url(\''. url::base() .'images/timebar.png\'); padding: 2px; font-size: 10px; font-family: Arial; color: #FFF; text-shadow: 0px 1px 0px #000; line-height: 20px; padding-left: 0px;"><span style="padding-left: 5px;"><div style="float: left; position: relative; top: 3px; left: 5px; background-image: url(\''. url::base() .'images/star.png\'); width: '. $star_width .'px; height: 12px;"></div><a href="'. url::base() .'projects/view/'. $row->uid .'/'. $row->pid .'/" style="text-decoration: none; color: #FFF;">'. $project_name .'</a></span><span style="float: right; padding-right: 5px;">'. date($datestring, strtotime($row->logtime)) .'</span></div>';
                $markup = $markup .'<div style="width: 260px; margin: 0px; height: 200px; border: 0px solid #F00;">';
				$markup = $markup .'<p><a href="'. url::base() .'updates/view/'. $row->id .'/"><img style="vertical-align: middle; border: 1px solid #999; padding: 1px; background: url('. $icon .'); background-repeat: no-repeat; background-position: 1px 1px; width: 260px; height: 200px;" src="'. url::base() .'images/crop_overlay.png" alt="update icon" /></a></p>';
				$markup = $markup .'<cite style="background-color: #D8D8D8; background-image: url(\''. url::base() .'/images/formbg.gif\'); background-repeat: repeat-x; -moz-opacity:.55; filter:alpha(opacity=55); opacity: .55; color: #000; position: relative; display: block; margin-left: auto; margin-right: auto; left: 2px; top: -63px; height: 30px; width: 240px; padding: 10px; border-top: 1px solid #888; font-weight: bold;"><span style="font-weight: 100; font-size: 9px; float: right; position: relative; top: -2px; text-align: right;">'. $row->views .'V<br />'. $kudos_model->kudos($row->id) .'K<br />'. $comment_model->comment_update_number($row->id) .'C</span></cite>';
				$markup = $markup .'<span style="color: #000; font-weight: 600; float: left; border: 0px solid #F00; height: 30px; width: 210px; text-shadow: 0px 1px 0px #AAA; position: relative; top: -105px; left: 8px; word-wrap: break-word;">'. $row->summary .'</span>';
				if (!empty($file_icon)) {
					$markup = $markup .'<img src="'. $file_icon .'" style="position: relative; top: -170px; left: -5px;" />';
				}
                $markup = $markup .'</div>';
				$markup = $markup .'<div style="margin-top: 4px; width: 264px; height: 12px; background-image: url(\''. url::base() .'images/grid_shadow.png\');"></div>';
				$markup = $markup .'</div>';
            }
        }

		$project_view->markup = $markup;
		$project_view->uid = $uid;
		$u_name = $user_model->user_information($uid);
		$project_view->u_name = $u_name['username'];

		// Pagination work.
		$project_view->pages = ceil(count($update_model->updates($uid, $pid)) / Kohana::config('projects.updates_page'));
		$project_view->page = $page;

		$this->template->content[] = $project_view;

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
		$project_model	= new Project_Model;
		$user_model		= new User_Model;
		$track_model	= new Track_Model;

		// If a $pid is specified, this means we are editing a project, hence we 
		// need to perform some authentication checks.
		if ($pid != FALSE)
		{
			// Check whether or not we own the project.
			if (!$project_model->check_project_owner($pid, $this->uid))
			{
				// You do not own this project.
				throw new Kohana_User_Exception('', '', 'permissions_error');
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
			$validate->add_rules('description', 'required');
			$validate->add_rules('cid', 'required', 'between[1, '. Kohana::config('projects.max_cid') .']');
			$validate->add_callbacks('contributors', array($this, '_validate_contributors'));

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
						// Your upload has failed.
						throw new Kohana_User_Exception('', '', 'upload_error');
					}
				}
				
				if ($pid == FALSE)
				{
					// Everything went great! Let's add the project.
					$new_pid = $project_model->manage_project(array(
						'uid'			=> $this->uid,
						'cid'			=> $cid,
						'name'			=> $name,
						'website'		=> $website,
						'summary'		=> $summary,
						'contributors'	=> $contributors,
						'description'	=> $description,
						'icon'			=> $icon_filename
						));

					// Send out email notifications.
					$track_list = $track_model->track_list($this->uid);

					$project_info = $project_model->project_information($new_pid);

					foreach ($track_list as $tid) {
						if ($tid != $this->uid) { 
							$user_information = $user_model->user_information($tid);
							if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
								$message = '<html><head><title>New WIPUP Project</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has created a new project called \''. $project_info['name'] .'\' ('. $project_info['summary'] .') on WIPUP.org. You can view this project by clicking the link below:</p><p><a href="'. url::base() .'projects/view/'. $new_pid .'/">'. url::base() .'projects/view/'. $new_pid .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
								$headers = 'MIME-Version: 1.0' . "\r\n" .
									'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
									'From: wipup@wipup.org' . "\r\n" .
									'Reply-To: wipup@wipup.org' . "\r\n" .
									'X-Mailer: PHP/' . phpversion();
								mail($user_information['email'], $this->username .' has made a new project on WIPUP', $message, $headers);
							}
						}
					}

					// Email to contributors too!
					$contributor_list = array();
					if (!empty($contributors)) {
						$contributors = explode(',', $contributors);
						foreach ($contributors as $con_id => $contributor) {
							preg_match('/\(.*\)/', $contributor, $matches);
							if (!empty($matches[0])) {
								// No brackets please...
								$alias_name = substr($matches[0], 1, -1);

								// Does this alias match a user?
								if ($user_model->uid($alias_name)) {
									$contributor_list[] = $user_model->uid($alias_name);
								}
							}
						}
					}

					foreach ($contributor_list as $tid) {
						if ($tid != $this->uid) {
						$user_information = $user_model->user_information($tid);
							if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
								$message = '<html><head><title>New WIPUP Project</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has created a new project called \''. $project_info['name'] .'\' ('. $project_info['summary'] .') on WIPUP.org. It turns out that you\'re a contributor to this project. You can view this project by clicking the link below:</p><p><a href="'. url::base() .'projects/view/'. $new_pid .'/">'. url::base() .'projects/view/'. $new_pid .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
								$headers = 'MIME-Version: 1.0' . "\r\n" .
									'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
									'From: wipup@wipup.org' . "\r\n" .
									'Reply-To: wipup@wipup.org' . "\r\n" .
									'X-Mailer: PHP/' . phpversion();
								mail($user_information['email'], $this->username .' has made a new project on WIPUP', $message, $headers);
							}
						}
					}
				}
				else
				{
					// Everything went great! Let's edit the project.
					$new_pid = $project_model->manage_project(array(
						'uid'			=> $this->uid,
						'cid'			=> $cid,
						'name'			=> $name,
						'website'		=> $website,
						'summary'		=> $summary,
						'contributors'	=> $contributors,
						'description'	=> $description,
						'icon'			=> $icon_filename
						), $pid);

					// Email to contributors too!
					$contributor_list = array();
					if (!empty($contributors)) {
						$contributors = explode(',', $contributors);
						foreach ($contributors as $con_id => $contributor) {
							preg_match('/\(.*\)/', $contributor, $matches);
							if (!empty($matches[0])) {
								// No brackets please...
								$alias_name = substr($matches[0], 1, -1);

								// Does this alias match a user?
								if ($user_model->uid($alias_name)) {
									$contributor_list[] = $user_model->uid($alias_name);
								}
							}
						}
					}

					$project_info = $project_model->project_information($new_pid);

					foreach ($contributor_list as $tid) {
						if ($tid != $this->uid) {
							$user_information = $user_model->user_information($tid);
							if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
								$message = '<html><head><title>WIPUP Project Edited</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has made edits to the project \''. $project_info['name'] .'\' ('. $project_info['summary'] .') on WIPUP.org. You\'re receiving this because it turns out that you\'re a contributor to this project. You can view this project by clicking the link below:</p><p><a href="'. url::base() .'projects/view/'. $new_pid .'/">'. url::base() .'projects/view/'. $new_pid .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
								$headers = 'MIME-Version: 1.0' . "\r\n" .
									'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
									'From: wipup@wipup.org' . "\r\n" .
									'Reply-To: wipup@wipup.org' . "\r\n" .
									'X-Mailer: PHP/' . phpversion();
								mail($user_information['email'], $this->username .' has made a new project on WIPUP', $message, $headers);
							}
						}
					}

				}

				if ($pid == FALSE)
				{
					// Redirect to the project itself.
					$this->session->set('notification', 'You\'ve got yourself a new project. Now let\'s fill it up with pure awesomeness.');
					url::redirect(url::base() .'projects/view/'. $this->uid .'/'. $new_pid .'/');
				}
				else
				{
					// Redirect to the project itself.
					$this->session->set('notification', 'We\'ve done the cosmetic updates, now it\'s time for you to add more WIPs.');
					url::redirect(url::base() .'projects/view/'. $this->uid .'/'. $new_pid .'/');
				}
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
					'description' => '',
					'cid' => ''
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
					'description' => '',
					'cid' => ''
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
	 * Deletes yourself as a contributor.
	 *
	 * @param int $pid The project ID of the project to delete from.
	 *
	 * @return null
	 */
	public function nocontribute($pid)
	{	
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$project_model = new Project_Model;

		if ($this->input->post()) {
			$contributor_projects = $project_model->contributor_projects($this->username);
			if (array_key_exists($pid, $contributor_projects)) {
				// Yep, we're a contributor alright, let's remove ourselves!
				$project_information = $project_model->project_information($pid);
				$contributor_array = explode(',', $project_information['contributors']);
				foreach ($contributor_array as $c_id => $c_name) {
					if (preg_match('/('. $this->username .')/i', $c_name)) {
						unset($contributor_array[$c_id]);
					}
				}

				// Recreate the contributor string.
				$contributor_string = '';
				foreach ($contributor_array as $contributor) {
					$contributor_string .= trim($contributor) .', ';
				}
				$contributor_string = substr($contributor_string, 0, -2);

				$project_model->manage_project(array('contributors' => $contributor_string), $pid);
			}

			// Redirect to the project itself.
			$this->session->set('notification', 'Now you can no longer contribute to the project. Killjoy.');
			url::redirect(url::base() .'projects/view/'. $project_information['uid'] .'/'. $pid .'/');
		}
		else
		{
			// Load the necessary view.
			$contributor_delete_view = new View('contributor_delete');

			$contributor_delete_view->projects = $project_model->projects($this->uid);
			$contributor_delete_view->pid = $pid;

			$this->template->content = array($contributor_delete_view);
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

		if ($this->input->post())
		{
			$new_pid = $this->input->post('new_pid');
			$contributor_projects = $project_model->contributor_projects($this->uid);

			// First check if you own the project.
			if (!empty($pid) && ($project_model->check_project_owner($pid, $this->uid) || array_key_exists($pid, $contributor_projects)) && $pid != $new_pid)
			{
				// Load necessary models.
				$update_model = new Update_Model;

				// Determine the value of the project's icon.
				$icon_filename	= $project_model->project_information($pid);
				$icon_filename	= $icon_filename['icon'];

				// Is there an existing image?
				if (!empty($icon_filename))
				{
					// Delete the file.
					unlink(DOCROOT .'uploads/icons/'. $icon_filename);
				}

				// Before the project database details are deleted, let's grab 
				// all the update information it contains. We can delete 
				// orphaned files later, but orphaned database entries are bad.
				$update_files = $update_model->updates($this->uid, $pid);

				// Delete the project.
				if ($new_pid == 0) {
					$new_pid == FALSE;
				}

				$project_model->delete_project($pid, $new_pid);

				if ($new_pid == FALSE)
				{
					// Now let's delete the files.
					foreach ($update_files as $row) {
						for ($i = 0; $i < 5; $i++)
						{
							if (!empty($row->{'filename'. $i}))
							{
								// Delete the file.
								unlink(DOCROOT .'uploads/files/'. $row->{'filename'. $i} .'.'. $row->{'ext'. $i});
								if (file_exists(DOCROOT .'uploads/icons/'. $row->{'filename'. $i} .'.jpg'))
								{
									unlink(DOCROOT .'uploads/icons/'. $row->{'filename'. $i} .'.jpg');
								}

								if (file_exists(DOCROOT .'uploads/icons/'. $row->{'filename'. $i} .'_crop.jpg'))
								{
									unlink(DOCROOT .'uploads/icons/'. $row->{'filename'. $i} .'_crop.jpg');
								}

								if (file_exists(DOCROOT .'uploads/files/'. $row->{'filename'. $i} .'_fit.jpg'))
								{
									unlink(DOCROOT .'uploads/files/'. $row->{'filename'. $i} .'_fit.jpg');
								}
							}
						}
					}
				}

				// Redirect to the update itself.
				$this->session->set('notification', 'Your project has been viciously fed to the dogs. It is no more.');
				url::redirect(url::base() .'profiles/projects/'. $this->uid .'/');
			}
			else
			{
				// Please ensure an ID is specified and you own the project.
				throw new Kohana_User_Exception('', '', 'permissions_error');
			}
		}
		else
		{
			// Load the necessary view.
			$project_delete_view = new View('project_delete');

			$project_delete_view->projects = $project_model->projects($this->uid);
			$project_delete_view->contributor_projects = $project_model->contributor_projects($this->uid);
			unset($project_delete_view->projects[$pid]);
			$project_delete_view->pid = $pid;

			$this->template->content = array($project_delete_view);
		}
	}

	/**
	 * Generates the markup required for displaying a project timeline.
	 *
	 * @param int $uid The user ID owning the project
	 * @param int $pid The project ID
	 *
	 * @return string
	 */
	public function _generate_project_timeline($uid, $pid)
	{
		// Load necessary models.
		$update_model = new Update_Model;

		$query = $update_model->updates($uid, $pid, 'DESC', 18);
        $markup = '';

        if (count($query) > 0) {
            foreach ($query as $row) {
				$icon = Updates_Controller::_file_icon($row->filename0, $row->ext0);
                // Build the markup.
                $markup = $markup .'<div>';
				if (!strpos($icon, 'images/icons')) { $markup_add = 'border: 1px solid #999; padding: 1px;'; } else { $markup_add = ''; }
				$markup = $markup .'<p><a href="'. url::base() .'updates/view/'. $row->id .'/"><img style="vertical-align: middle; '. $markup_add .'" src="'. $icon .'" alt="update icon" /></a></p>';
                $markup = $markup .'<h3><a href="'. url::base() .'updates/view/'. $row->id .'/">'. $row->summary .'</a></h3><span>'. date('jS F Y', strtotime($row->logtime)) .'</span>';
                $markup = $markup .'</div>';
            }
        }
        return $markup;
	}

	/**
	 * Validates the contributors list.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_contributors(Validation $array, $field)
	{
		$contributors = explode(',', $array[$field]);
		if (count($contributors) > 4) {
			$array->add_error($field, 'contributors');
		}
	}

}
