<?php defined('SYSPATH') or die('No direct script access.');

/*=====================================

Formo csrf Plugin

(based on driver by Eduard B)

Makes sure form was posted from appropriate
domain using session data.

=====================================*/

class Formo_csrf {

	protected $form;
	
	public $csrf_token;
	protected $last_token;
	protected $error = FALSE;

	public function __construct( & $form)
	{
		if (request::is_ajax())
			return;
			
		Event::add('formo.post_validate', array($this, 'post_validate'));
		Event::add('formo.pre_render', array($this, 'pre_render'));
		
		$this->form = $form;
		$this->form
			->bind('csrf_token', $this->csrf_token);
			
		$this->last_token = Session::instance()->get_once('formo_csrf_token', FALSE);
	}
	
	public static function load( & $form)
	{
		return new Formo_csrf($form);
	}
	
	public function post_validate()
	{
		if (Input::instance()->post('csrf') !== $this->last_token)
		{
			$this->form->set('error', TRUE);
		}
	}
	
	public function pre_render()
	{
		$this->csrf_token = text::random('alnum', 16);
		Session::instance()->set('formo_csrf_token', $this->csrf_token);

		$this->form->add_hidden('csrf', $this->csrf_token);		
	}

}