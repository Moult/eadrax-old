<?php defined('SYSPATH') or die('No direct script access.');

/*======================================

This is an example of a Formo Plugin

Once loaded, this plugin adds the attribute
"comment" to each form element. The comment
will appear where errors show up unless
there is an error.


You will have to load this plugin either at
runtime using $form->plugin('comments')
or in the formo config file.

======================================*/

class Formo_comments {

	public $comment_class = 'comment';
	
	public function __construct()
	{		
		Event::add('formoel.preload', array($this, 'preload'));
		Event::add('formoel.pre_render', array($this, 'add_comment'));
	}
	
	public static function load()
	{
		return new Formo_comments();
	}
	
	public function preload()
	{
		$data = Event::$data;
		$element = $data['object'];
				
		$element->add_attribute('comment');
		$element->add_attribute('passed');
	}
	
	public function add_comment()
	{
		$element = Event::$data;
		if ( ! $element->error AND ! $element->was_validated)
		{
			$element->error_msg_class = $this->comment_class;
			$element->error = $element->comment;
		}
		elseif ($element->was_validated AND ! $element->error)
		{
			$element->error_msg_class = $this->comment_class;
			$element->error = $element->passed;
		}
	}
		
}