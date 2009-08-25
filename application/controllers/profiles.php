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
	
	public function update($id = NULL)
	{
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
	
	public function change_password($id = NILL) {
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