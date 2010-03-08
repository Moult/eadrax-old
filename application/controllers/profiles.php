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
		if ($uid == FALSE) {
			$this->restrict_access();
			$uid = $this->uid;
		}

		// Load necessary models.
		$project_model	= new Project_Model;
		$update_model	= new Update_Model;
		$user_model		= new User_Model;

		// Load the main profile view.
		$profile_view = new View('profiles/index');
		$profile_view->user = $user_model->user_information($uid);
		//$profile_view->user = Authlite::factory()->get_user();

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
				'<ul style="margin-left: 30px; font-size: 16px;">$1</ul>',
				'<li>$1</li>'
			);
			 
			$description = preg_replace($simple_search, $simple_replace, $description);

            $format = 'style="margin-bottom: 10px;"';
            $description = '<p '. $format .'>'. $description .'</p>';
            $description = preg_replace("/(?:\r?\n)+/", '</p><p '. $format .'>', $description);

			// Let's do some really nasty fixing to maintain HTML validity.
			$description = preg_replace(array('/<p '. $format .'><ul style="margin-left: 30px; font-size: 16px;"><\/p>/', '/<p '. $format .'><\/ul><\/p>/', '/<p '. $format .'><li>(.*?)<\/li><\/p>/'), array('<ul style="margin-left: 30px; font-size: 16px;">', '</ul>', '<li>$1</li>'), $description);

			$$project_view->description = $description;

			$$project_view->timeline = Projects_Controller::_generate_project_timeline($uid, $pid);
			$$project_view->categories = $project_model->categories();
			$$project_view->uid = $uid;
			array_push($template_array, $$project_view);

			// We need to build an array of PIDs to send to the view so that 
			// jquery effects can be done on specific div ids.
			$pid_array[] = $pid;
		}

		// Let's sneak the profile view into the beginning of the page.
		array_unshift($template_array, $profile_view);

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

			// No support for avatars just yet. TODO

			// Begin to validate the information.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('email', 'email');
			$validate->add_rules('dd', 'digit', 'length[1,2]');
			$validate->add_rules('mm', 'digit', 'length[1,2]');
			$validate->add_rules('yyyy', 'digit', 'length[4]');
			$validate->add_rules('msn', 'email');
			$validate->add_rules('gtalk', 'email');
			$validate->add_rules('website', 'url');
			$validate->add_rules('location', 'length[2, 15]');
			$validate->add_callbacks('gender', array($this, '_validate_gender'));
			$validate->add_callbacks('dd', array($this, '_validate_dob'));

			if ($validate->validate())
			{
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
					'location' => $location
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
					'location' => ''
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
				'location' => ''
			);

			$update_profile_view->form = $user_model->user_information($this->uid);

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
		if ($array['yyyy'] > 2003 || $array['yyyy'] < 1933 || $array['mm'] > 12 || $array['mm'] < 1 || $array['dd'] < 1 || $array['dd'] > 31) {
			$array->add_error($field, 'dob');
		}
	}
}
