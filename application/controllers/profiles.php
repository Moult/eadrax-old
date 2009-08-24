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
	
	public function __construct()
	{
		parent::__construct();
		if(!$this->authlite->logged_in()){
			url::redirect('');
			exit;
		}
	}
	
	public function index()
	{
		$content = new View('profiles/index');
		$content->user = Authlite::factory()->get_user();
		$this->template->content = array($content);
	}
	
	/*public function update($id = NULL)
	{	
		$content = new View('profiles/update');
		$content->user = ORM::factory('user', $id);
		
		$post = new Validation($_POST);
        $post->pre_filter('trim',true)
        	 ->pre_filter('strtolower', 'website');
		
         // Rules
        $post->add_rules('email', 'valid::email');
    
		$content->repopulate = $post;
		
		if($post->validate()) {
			$content->user->description = $post->description;
			$content->user->email = $post->email;
			$content->user->msn = $post->msn;
			$content->user->gtalk = $post->gtalk;
			$content->user->yahoo = $post->yahoo;
			$content->user->skype = $post->skype;
			$content->user->website = $post->website;
			$content->user->location = $post->location;
			$content->user->dob = $post->dob;
			$content->user->gender = $post->gender;
			
			if($content->user->save()){
                url::redirect('profiles');
            }
		}
		
		// Return errors from the language file.
		$content->error = $post->errors('profile_errors');
		
		$this->template->content = array($content);
	}*/
	
	public function update($id = NULL)
	{
		$user = ORM::factory('user', $id);
		
		$form = Formo::factory()
			->add('description', array('value'=>$user->description))
			->add('email', array('value'=>$user->email))
			->add('msn', array('value'=>$user->msn))
			->add('gtalk', array('value'=>$user->gtalk))
			->add('yahoo', array('value'=>$user->yahoo))
			->add('skype', array('value'=>$user->skype))
			->add('website', array('value'=>$user->website))
			->add('location', array('value'=>$user->location))
			->add_select('gender', Kohana::config('profiles.gender'), array('value'=>$user->gender))
			->add('submit');
			
		$form->add_rule('name', array('length[10,20]', 'email'));
		
		if($form->validate()) {
			$user->description = $form->description->value;
			$user->email = $form->email->value;
			$user->msn = $form->msn->value;
			$user->gtalk = $form->gtalk->value;
			$user->yahoo = $form->yahoo->value;
			$user->skype = $form->skype->value;
			$user->website = $form->website->value;
			$user->location = $form->location->value;
			$user->gender = $form->gender->value;
			
			if($user->save()){
                // Setting message for user
                $this->session->set_flash('message', 'You have successfully added "'.$_POST['title'].'".');
                url::redirect('profiles');
            }
		}
		
		$content = new View('profiles/update', $form->get(TRUE));
		$content->user = $user;
		
		$this->template->content = array($content);
	}
}
