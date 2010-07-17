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
 * @package		Profile
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Users controller for tasks related to user management
 *
 * @category	Eadrax
 * @package		Profile
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Profiles_Controller extends Openid_Controller {
	
	public function index($uid = FALSE)
	{
		Projects_Controller::view($uid);
	}

	public function projects($uid = FALSE)
	{
		if ($uid == FALSE) {
			$this->restrict_access();
			$uid = $this->uid;
		}

		// Load necessary models.
		$project_model	= new Project_Model;
		$update_model	= new Update_Model;
		$user_model		= new User_Model;
		$kudos_model	= new Kudos_Model;
		$comment_model	= new Comment_Model;

		// Load the main profile view.
		$project_top_view = new View('project_top');
		$user_information = $user_model->user_information($uid);
		$project_top_view->user = $user_information;

		// Calculate age from date of birth.
		if(!empty($user_information['dob']))
		{
			list($dd, $mm, $yyyy) = explode('/', $user_information['dob']);
			$age = date('Y') - $yyyy;

			if(date('m') < $mm || (date('m') == $mm && date('d') < $dd)) {
				$age--;
			}
		}
		else
		{
			$age = '';
		}

		$project_top_view->age = $age;

		// Parse featured update.
		if ($user_information['featured'] != 0)
		{
			$featured_information = $update_model->update_information($user_information['featured']);
			list($width, $height, $type, $attr) = getimagesize(DOCROOT .'uploads/files/'. $featured_information['filename0'] .'.'. $featured_information['ext0']);

			if ($width > 850) {
				$featured_filename = $featured_information['filename0'] .'_fit.jpg';
			} else {
				$featured_filename = $featured_information['filename0'] .'.'. $featured_information['ext0'];
			}

			if ($height > 250) {
				$featured_height = $height/15;
			} else {
				$featured_height = 0;
			}

			$project_top_view->featured_filename = $featured_filename;
			$project_top_view->featured_height = $featured_height;
			$project_top_view->featured_project_information = $project_model->project_information($featured_information['pid']);
		}

		$project_top_view->uid = $uid;
		$project_top_view->browseby = $this->uri->segment(2);

		foreach ($project_model->projects($uid) as $pid => $p_name) {
			$pid_array[] = $pid;
		}

		$project_top_view->pid_array = $pid_array;

		// Initialise all the arrays required in the loop.
		$project_updates = array();
		$timelines = array();
		$template_array = array();
		$pid_array = array();

		foreach ($project_model->projects($uid) as $pid => $p_name)
		{
			// Create each view on the fly!
			$project_view = 'project_view_'. $pid;
			$$project_view = new View('projects');
			$$project_view->project = $project_model->project_information($pid);

			// Parse the description
			$description = $$project_view->project['description'];

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

            $format = 'style="margin-bottom: 10px;"';
            $description = '<p '. $format .'>'. $description .'</p>';
            $description = preg_replace("/(?:\r?\n)+/", '</p><p '. $format .'>', $description);

			// Let's do some really nasty fixing to maintain HTML validity.
			$description = preg_replace(array('/<p '. $format .'><ul><\/p>/', '/<p '. $format .'><\/ul><\/p>/', '/<p '. $format .'><li>(.*?)<\/li><\/p>/'), array('<ul>', '</ul>', '<li>$1</li>'), $description);

			$$project_view->description = $description;

			$$project_view->timeline = Projects_Controller::_generate_project_timeline($uid, $pid);
			$$project_view->categories = $project_model->categories();
			$$project_view->uid = $uid;

			// Generate the mini preview.
			$mini = $update_model->updates($uid, $pid, 'DESC', 5);
			$mini_markup = '';

			$mini_opacity = 90;
			foreach ($mini as $row) {
				$icon = Updates_Controller::_file_icon($row->filename0, $row->ext0);
				$mini_markup = $mini_markup .'<div class="mini" style="-moz-opacity:.'. $mini_opacity .'; filter:alpha(opacity='. $mini_opacity .'); opacity: .'. $mini_opacity .'; background-image: url('. url::base() .'images/mini_icon.png); background-position: 2px; 2px; background-repeat: no-repeat;">';
				$mini_markup = $mini_markup .'<div style="padding: 1px; border: 1px solid #CCC;"><a href="'. url::base() .'/updates/view/'. $row->id .'/"><img style="vertical-align: middle; background-image: url('. $icon .');" src="'. url::base() .'images/mini_overlay.png" alt="update icon" /></a></div>';
                $mini_markup = $mini_markup .'</div>';
				$mini_opacity = $mini_opacity - 20;
			}

			$$project_view->mini_markup = $mini_markup;

			array_push($template_array, $$project_view);

			// We need to build an array of PIDs to send to the view so that 
			// jquery effects can be done on specific div ids.
			$pid_array[] = $pid;
		}

		// Let's sneak the profile view into the beginning of the page.
		array_unshift($template_array, $project_top_view);

		// Then finally add the footer.
		$random_update_view = new View('random_update');

		// Let's find out some random updates!
		if ($update_model->update_number($uid) > 0)
		{
			$display_random = TRUE;
			$random_query = $update_model->update_number_random($uid);
			$n = 0;
			$random_data = array();

			foreach ($random_query->result() as $row)
			{
				$random_data[$n]['summary'] = $row->summary;
				$random_data[$n]['id'] = $row->id;
				$random_data[$n]['uid'] = $row->uid;

				// Let's parse the thumbnails.
				$random_data[$n]['filename0'] = Updates_Controller::_file_icon($row->filename0, $row->ext0);

                // Determind the sizes for offsetting.
                $path = substr($random_data[$n]['filename0'],strlen(url::base()));
                $path = DOCROOT . $path;
                $random_data[$n]['thumb_height'] = getimagesize($path);
                $random_data[$n]['thumb_width']  = $random_data[$n]['thumb_height'][0];
                $random_data[$n]['thumb_height'] = $random_data[$n]['thumb_height'][1];
                $random_data[$n]['thumb_offset'] = $random_data[$n]['thumb_height']/2;

                // Right, now find out the project name.
                if ($row->pid == 0) {
                    $random_data[$n]['project_name'] = 'Uncategorised';
                    $random_data[$n]['pid'] = 0;
                } else {
                    $random_data[$n]['pid'] = $row->pid;
					$random_data[$n]['project_name'] = $project_model->project_information($row->pid);
					$random_data[$n]['project_name'] = $random_data[$n]['project_name']['name'];
                }

                $n++;
			}

			// Send all the data we collected to the view...
			$random_update_view->random_data = $random_data;
			$random_update_view->user = $user_model->user_information($uid);
		}

		$template_array[] = $random_update_view;

		$this->template->content = $template_array;
		//print_r($template_array);
		$this->template->pids = $pid_array;
	}

	public function view($uid = FALSE)
	{
		// This is purely for SEO and convenience reasons.
		// Load necessary models.
		$user_model	= new User_Model;

		// If they provide a username and not a UID we want to support that too!
		if ($uid != FALSE && $user_model->uid($uid) != FALSE) {
			$uid = $user_model->uid($uid);
		}
		$this->index($uid);
	}
	
	public function update($id = NULL)
	{
		// Restrict any guests.
		$this->restrict_access();

		// Load necessary models.
		$user_model	= new User_Model;

		if ($this->uid != $id) {
			die('You can only update your own profile');
		}
		
		if ($this->input->post())
		{
			$gender = $this->input->post('gender');
			$email = $this->input->post('email');
			$description = $this->input->post('description');
			$dd = $this->input->post('dd');
			$mm = $this->input->post('mm');
			$yyyy = $this->input->post('yyyy');
			$msn = $this->input->post('msn');
			$gtalk = $this->input->post('gtalk');
			$yahoo = $this->input->post('yahoo');
			$skype = $this->input->post('skype');
			$website = $this->input->post('website');
			$location = $this->input->post('location');

			// Let's check for existing avatars
			$user_information = $user_model->user_information($this->uid);
			$avatar_filename = $user_information['avatar'];

			// Begin to validate the information.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('email', 'email');
			$validate->add_rules('dd', 'digit', 'length[1,2]');
			$validate->add_rules('mm', 'digit', 'length[1,2]');
			$validate->add_rules('yyyy', 'digit');
			$validate->add_rules('msn', 'email');
			$validate->add_rules('gtalk', 'email');
			$validate->add_rules('website', 'url');
			$validate->add_rules('location', 'length[2, 15]');
			$validate->add_callbacks('gender', array($this, '_validate_gender'));
			$validate->add_callbacks('dd', array($this, '_validate_dob'));

			if ($validate->validate())
			{
				if (!empty($_FILES['avatar']['name']))
				{
					$files = new Validation($_FILES);
					$files = $files->add_rules('avatar', 'upload::valid', 'upload::type['. Kohana::config('profiles.filetypes') .']', 'upload::size['. Kohana::config('profiles.avatar_upload_limit') .']');

					if ($files->validate())
					{
						// If there is an existing avatar in place.
						if (!empty($avatar_filename))
						{
							// ... delete it!
							unlink(DOCROOT .'uploads/avatars/'. $avatar_filename .'.jpg');
							unlink(DOCROOT .'uploads/avatars/'. $avatar_filename .'_small.jpg');
						}

						// Upload the file as normal.
						$filename = upload::save('avatar', time() . strtolower($_FILES['avatar']['name']), DOCROOT .'uploads/avatars/');

						// Determine the file name.
						$extension = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
						$avatar_filename = substr(basename($filename), 0, -strlen($extension)-1);

						list($width, $height, $type, $attr) = getimagesize($filename);

						// Create a cropped thumbnail.
						if ($extension == 'jpg') {
							$myImage = imagecreatefromjpeg($filename);   
						} elseif ($extension == 'gif') {
							$myImage = imagecreatefromgif($filename);   
						} elseif ($extension == 'png') {
							$myImage = imagecreatefrompng($filename);   
						}

						// We no longer need the original image.
						unlink(DOCROOT .'uploads/avatars/'. $avatar_filename .'.'. $extension);
						  
						if ($width > $height) {  
							$cropWidth   = $height;   
							$cropHeight  = $height;   
							$c1 = array("x"=>($width-$cropWidth)/2, "y"=>0);  
						} else {
							$cropWidth   = $width;   
							$cropHeight  = $width;   
							$c1 = array("x"=>0, "y"=>($height-$cropHeight)/8);  
						}

						// Creating the thumbnail  
						$thumb = imagecreatetruecolor(80, 80);   
						imagecopyresampled($thumb, $myImage, 0, 0, $c1['x'], $c1['y'], 80, 80, $cropWidth, $cropHeight);   
						   
						//final output    
						imagejpeg($thumb, DOCROOT .'uploads/avatars/'. substr(basename($filename), 0, -4) .'.jpg', 80);
						Image::factory(DOCROOT .'uploads/avatars/'. substr(basename($filename), 0, -4) .'.jpg')->resize(50, 50, Image::AUTO)->save(DOCROOT .'uploads/avatars/'. substr(basename($filename), 0, -4) .'_small.jpg');
						imagedestroy($thumb);
					}
					else
					{
						die ('Your upload has failed, please check your file extension.');
					}
				}

				$dob = $dd .'/'. $mm .'/'. $yyyy;

				// Everything went great! Let's add the update.
				$uid = $user_model->manage_user(array(
					'gender' => $gender,
					'email' => $email,
					'description' => $description,
					'dob' => $dob,
					'msn' => $msn,
					'gtalk' => $gtalk,
					'yahoo' => $yahoo,
					'skype' => $skype,
					'website' => $website,
					'location' => $location,
					'avatar' => $avatar_filename
				), $this->uid);

				// Then load our success view.
				$update_profile_success_view = new View('update_profile_success');

				// Generate content.
				$this->template->content = array($update_profile_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$update_profile_view = new View('update_profile');
				$update_profile_view->form = arr::overwrite(array(
					'email' => '',
					'description' => '',
					'msn' => '',
					'gtalk' => '',
					'yahoo' => '',
					'skype' => '',
					'website' => '',
					'location' => '',
					'gender' => '',
					'dd' => '',
					'mm' => '',
					'yyyy' => ''
				), $validate->as_array());
				$update_profile_view->errors = $validate->errors('profile_errors');

				// Generate the content.
				$this->template->content = array($update_profile_view);
			}

		}
		else
		{
			// Load the necessary view.
			$update_profile_view = new View('update_profile');

			// If we didn't press submit, we want a blank form.
			$update_profile_view->form = array(
				'email' => '',
				'description' => '',
				'msn' => '',
				'gtalk' => '',
				'yahoo' => '',
				'skype' => '',
				'website' => '',
				'location' => '',
				'gender' => '',
				'dd' => '',
				'mm' => '',
				'yyyy' => ''
			);

			$update_profile_view->form = $user_model->user_information($this->uid);

			// We need to manually get the date fields of the profile.
			list($dd, $mm, $yyyy) = explode('/', $update_profile_view->form['dob']);
			$update_profile_view->form['dd'] = $dd;
			$update_profile_view->form['mm'] = $mm;
			$update_profile_view->form['yyyy'] = $yyyy;

			$this->template->content = array($update_profile_view);
		}
	}
	
	public function change_password($id = NULL)
	{
		$this->restrict_access();
		$user = ORM::factory('user', $id);
		
		$form = Formo::factory()
			->add('password')->type('password')->rule('matches[password2]', 'The password does not match')
    		->add('password2')->type('password')->label('Confirm Password')
			->add('submit');
			
		// $form->add_rule('password', array('matches[password2]', 'Does not match'));
		
		if($form->validate()) {
			$user->password = $this->authlite->hash($form->password->value);
			
			if($user->save()){
                // Setting message for user
                url::redirect('profiles');
            }
		}
		
		$content = new View('profiles/password', $form->get(TRUE));
		
		$this->template->content = array($content);
	}

	public function options($id = NULL)
	{
		// Restrict any guests.
		$this->restrict_access();

		// Load necessary models.
		$user_model	= new User_Model;

		if ($this->uid != $id) {
			die('You can only update your own options');
		}
		
		if ($this->input->post())
		{
			$change_password = $this->input->post('change_password');
			$old_password = $this->input->post('old_password');
			$new_password = $this->input->post('new_password');
			$repeat_new_password = $this->input->post('repeat_new_password');
			$email_notifications = $this->input->post('email_notifications');
			$email_public = $this->input->post('email_public');
			$allow_trackers = $this->input->post('allow_trackers');

			// Begin to validate the information.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			if ($change_password == 1) {
				$validate->add_rules('old_password', 'required');
				$validate->add_callbacks('old_password', array($this, '_validate_password'));
				$validate->add_rules('new_password', 'required');
				$validate->add_rules('repeat_new_password', 'required', 'matches[new_password]');
			}

			if ($validate->validate())
			{
				// Everything went great! Let's update the options.
				$manage_array = array();

				if ($change_password == 1) { 
					$manage_array['password'] = md5($new_password);
				}

				if ($email_notifications == 1) {
					$manage_array['notifications'] = 1;
				} else {
					$manage_array['notifications'] = 0;
				}
				
				if ($email_public == 1) {
					$manage_array['email_public'] = 1;
				} else {
					$manage_array['email_public'] = 0;
				}

				if ($allow_trackers == 1) {
					$manage_array['enable_tracking'] = 1;
				} else {
					$manage_array['enable_tracking'] = 0;
				}

				$uid = $user_model->manage_user($manage_array, $this->uid);

				// Then load our success view.
				$update_options_success_view = new View('update_options_success');

				// Generate content.
				$this->template->content = array($update_options_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$options_view = new View('options');
				$options_view->form = arr::overwrite(array(
					'change_password' => '',
					'email_notifications' => '',
					'email_public' => '',
					'allow_trackers' => ''
				), $validate->as_array());
				$options_view->errors = $validate->errors('options_errors');

				// Generate the content.
				$this->template->content = array($options_view);
			}

		}
		else
		{
			// Load the necessary view.
			$options_view = new View('options');

			// If we didn't press submit, we want a blank form.
			$user_information = $user_model->user_information($this->uid);
			$options_view->form = array(
				'change_password' => 0,
				'email_notifications' => $user_information['notifications'],
				'email_public' => $user_information['email_public'],
				'allow_trackers' => $user_information['enable_tracking']
			);

			$this->template->content = array($options_view);
		}
	}

	/**
	 * Validates the gender.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_gender(Validation $array, $field)
	{
		if ($array[$field] != 'Male' && $array[$field] != 'Female' && $array[$field] != 'Confused' && $array[$field] != 'Dog')
		{
			$array->add_error($field, 'gender');
		}
	}

	/**
	 * Validates the DOB.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_dob(Validation $array, $field)
	{
		if ($array['yyyy'] > 2003 || ($array['yyyy'] < 1933 && $array['yyyy'] != 0) || $array['mm'] > 12 || $array['mm'] < 0 || $array['dd'] < 0 || $array['dd'] > 31) {
			$array->add_error($field, 'dob');
		}
	}

	/**
	 * Validates the existing password.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_password(Validation $array, $field)
	{
		$user_model = new User_Model;

		$existing_password = $user_model->user_information($this->uid);
		if (md5($array['old_password']) != $existing_password['password']) {
			$array->add_error($field, 'password');
		}
	}
}
