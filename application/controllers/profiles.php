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

		$project_updates = array();
		$timelines = array();
		$template_array = array();
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

			$$project_view->description = $description;

			$$project_view->timeline = Projects_Controller::_generate_project_timeline($pid);
			$$project_view->categories = $project_model->categories();
			array_push($template_array, $$project_view);
		}

		// Let's sneak the profile view into the beginning of the page.
		array_unshift($template_array, $profile_view);

		$this->template->content = $template_array;
	}
	
	public function update($id = NULL)
	{
		$this->restrict_access();
		$user = ORM::factory('user', $id);
		
		$form = Formo::factory()
			->add('description', array('value'=>$user->description, 'required'=>FALSE))
			->add('email', array('value'=>$user->email, 'required'=>FALSE))
			->add('msn', array('value'=>$user->msn, 'required'=>FALSE))
			->add('gtalk', array('value'=>$user->gtalk, 'required'=>FALSE))
			->add('yahoo', array('value'=>$user->yahoo, 'required'=>FALSE))
			->add('skype', array('value'=>$user->skype, 'required'=>FALSE))
			->add('website', array('value'=>$user->website))
			->add('location', array('value'=>$user->location))
			->add('dob', array('value'=> $user->dob,'required'=>FALSE))
			->add_select('gender', Kohana::config('profiles.gender'), array('value'=>$user->gender))
			->add('submit');
		
		if($form->validate()) {
			$user->description = $form->description->value;
			$user->email = $form->email->value;
			$user->msn = $form->msn->value;
			$user->gtalk = $form->gtalk->value;
			$user->yahoo = $form->yahoo->value;
			$user->skype = $form->skype->value;
			$user->website = $form->website->value;
			$user->location = $form->location->value;
			$user->dob = $form->dob->value;
			$user->gender = $form->gender->value;
			
			if($user->save()){
                // Setting message for user
                url::redirect('profiles');
            }
		}
		
		$content = new View('profiles/update', $form->get(TRUE));
		$content->user = $user;
		
		$this->template->content = array($content);
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
}
