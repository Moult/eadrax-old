<?php defined('SYSPATH') or die('No direct script access.');

/*==============================================

	Once you include the formo module in your application/config file
	you can view these demos at 'formo_demo', 'formo_demo/demo2', 'formo_demo/demo3', etc.

==============================================*/



class Formo_demo_Controller extends Template_Controller {

	public $template = 'formo_template';
	
	protected $title = "Formo Version 1.1.6";
	protected $scripts = FALSE;
	protected $header;
	protected $content;
	
	private $msg = '<div class="success">Success!</div>';
	
	public function __construct()
	{
		parent::__construct();

		$this->template
			->bind('title', $this->title)
			->bind('scripts', $this->scripts)
			->bind('header', $this->header)
			->bind('content', $this->content);
		
		(Router::$method == 'demo8' AND $this->scripts = TRUE);
		if (Router::$method != 'index')
		{
			$this->header = new View('headers/'.Router::$method);
		}		
	}
		
	/**
	 * Formo Playground. practice building forms
	 *
	 */
	public function index()
	{
		$form = Formo::factory()
			->add('submit');
				
		
		$this->content = $form;
	
		if ($form->validate())
		{
			$this->_success();
		}
	}
							
	/**
	 * A basic form. All fields are required except "name"
	 *
	 */
	public function demo1()
	{		
		$form = Formo::factory()
			->add('email')
			->add('name', array('required'=>FALSE))
			->add('textarea', 'notes')
			->add('submit');
			
		$form->add_rule('name', array('length[10,20]', 'email'));

												
		$this->content = $form;
		$form->validate();
		
		($form->validate() AND $this->_success());
	}
					
	/**
	 * this is an example of working with arrays
	 * to test out the file upload capability, set the uploads folder inside /formo to 777
	 *
	 */ 
	public function demo2()
	{
		$form = Formo::factory()
			->add('name')
			->add('email')
			->add('file', 'image', 'allowed_types=png|gif|jpg,max_size=500K,upload_path=modules/formo/uploads/')
			->add('submit', 'Submit', 'class=submit');
		
		
		// just messin' around with syntax here
		$form->name->class = 'input';
		
		// notice the TRUE. that means as an array
		$data = $form->get(TRUE);
		
		
		$this->content = new View('demo2', $data);
		
		($form->validate() AND $this->_success());		
	}
	
	/**
	 * A more complicated form that includes settings set on the fly.
	 * Generally, you would set these settings in the config file
	 *
	 */
	public function demo3()
	{	
		$defaults = array('email'=>array('label'=>'E-mail'));
		$favorites = array('_blank_'=>'', 'Run'=>'run', 'Jump'=>'jump', 'Swim'=>'swim');
		
		$form = Formo::factory()
			->set('defaults', $defaults) // you can add defaults on the fly like so
			->add('username')
			->add('email')
			->add('phone')

			->add_html('<div style="height:15px"></div>', 'space')
			->add_select('activity', $favorites, array('label'=>'Favorite Activity', 'required'=>TRUE, 'style'=>'width:150px'))

			->add_html('<div style="height:15px"></div>', 'space2')
			->add('password', 'password', 'required=1')
			->add('password', 'password2', 'label=Confirm')
			
			->add_html('<div style="height:15px"></div>', 'space3')
			->add('textarea', 'notes')
			->add('submit', 'Submit', 'class=submit')
			
			->label_filter('ucwords')
			->pre_filter('all', 'trim')
			->add_rule('password', 'matches[password2]', "Doesn't match")
			->add_rule('password2', 'matches[password]', "Doesn't match")
			->add_rule('phone', 'phone[10]');
		
		
		$this->template->content = $form;

		($form->validate() AND $this->_success());
	}

	/**
	 * Radio and checkbox groups intermingled with other stuff
	 *
	 */	
	public function demo4()
	{
		$skills = array(1=>'poet', 25=>'artist', 3=>'television');
		$hobbies = array('run'=>'run', 'jump'=>'jump', 'swim'=>'swim');

		$form = Formo::factory()
			->add('name')
			->add('state')
			->add_group('skill', $skills)
			->add_group('hobbies[]', $hobbies)
			->add('submit');
			
			
		$this->content = $form;
										
		($form->validate() AND $this->_success());
	}
	
	/**
	 * Using the comments plugin
	 *
	 */	
	public function demo5()
	{
		$form = Formo::factory()
			->plugin('comments')
			->add('username', 'comment=Please do not be obscene,passed=Thank You')
			->add('email', 'comment=Must be a valid email,passed=Good Job,rule=email')
			->add('submit');	
		
		
		$this->content = $form;
		
		($form->validate() AND $this->_success());
	}
	
	/**
	 * Captcha demo
	 *
	 */	
	public function demo6()
	{
		$form = Formo::factory()
			->plugin('csrf')
			->add('textarea', 'message')
			->add('captcha', 'security')
			->add('submit');
		

		$this->content = $form;
		
		($form->validate() AND $this->_success());
	}
	
	// alternate syntaxes galore demo
	// Formo's syntax just got more flexible
	public function demo7()
	{
		$favorites = array('_blank_'=>'', 'Run'=>'run', 'Jump'=>'jump', 'Swim'=>'swim');

		$form = Formo::factory()
			// you can chain using last used object
			->add('email')->label('E-mail')->value('eat@joes.com')
			
			// same as ->add_rule('phone', 'phone', 'Not a valid phone')
			->add('phone')->rule('phone', 'Not a valid phone')
			
			// you can set or chane the type like this
			->add('favorites')->type('select')->values($favorites)
			->add_textarea('notes')
			->add('submit')
			
			// sets label for phone
			// same as ->set('phone', 'label', 'Home Phone')
			->phone('label', 'Home Phone')
			
			// sets label for notes
			// same as ->set('label', 'notes', 'My Notes')
			->label('notes', 'My Notes')
			
			// adds value to notes
			// same as ->set('notes', 'value', '...')
			->notes('These are my original notes');
						

		$this->content = $form;
		
		($form->validate() AND $this->_success());
	}
	
	// oh baby, it's auto ajax validation!
	public function demo8()
	{
		$hobbies = array('_blank_'=>'', 'Run'=>'run', 'Jump'=>'jump', 'Swim'=>'swim');
		$skills = array(1=>'poet', 25=>'artist', 3=>'television');
		$months = array('January','February','March','April','May','June','July',
		                'August','September','October','November','December');

		$form = Formo::factory()
			->plugin('ajaxval')
			
			->add('autocomplete', 'month', array('data'=>implode('|',$months)))
			->add_html('<div style="height:20px"></div>', 'space1')

			->add('username')
			
			->add('password', 'password')
			->add('password', 'password2')->label('Confirm Password')->rule('matches[password]', 'Does not match')
			->add_html('<div style="height:20px"></div>', 'space2')
			
			->add('email')
			->add('phone')
			->add_select('hobbies', $hobbies)
			->add_group('skills[]', $skills)
			->add('textarea', 'notes')
			->add_submit('submit');


		$this->content = $form;

		($form->validate() AND $this->_success());
	}
		
	private function _success()
	{
		return $this->content = $this->msg.$this->content;
	}
			
}