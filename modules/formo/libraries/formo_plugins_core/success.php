<?php defined('SYSPATH') or die('No direct script access.');

/*=====================================

Formo Plugin Success

Run successive on_success or on_failure
functions automatically upon validation success
and failure.

=====================================*/


class Formo_success {

	public $form;
	
	public $on_success = array();
	public $on_failure = array();
	
	public function __construct( & $form)
	{
		$this->form =& $form;
		Event::add('formo.post_validate', array($this, 'do_functions'));
		
		$this->form
			->add_function('success', array($this, 'success'))
			->add_function('failure', array($this, 'failure'));
	}
	
	public static function load( & $form)
	{
		return new Formo_success($form);
	}
			
	public function success($function, $args=NULL)
	{
		$this->on_success[] = array
		(
			'function'	=> $function,
			'args'		=> $args
		);
	}
	
	public function failure($function, $args=NULL)
	{
		$this->on_failure[] = array
		(
			'function'	=> $function,
			'args'		=> $args
		);
	}
	
	public function do_functions()
	{
		$use = ( ! $this->form->_validated) ? 'on_failure' : 'on_success';

		foreach ($this->$use as $array)
		{
			list($function, $args) = Formo::into_function($array['function']);
			
			if ($array['args'])
			{
				array_unshift($args, $array['args']);
			}
			array_unshift($args, $this->form->get_values());

			switch ($function)
			{
				case 'url::redirect':
					url::redirect($args[count($args)-1]);
					break;
				default:
					call_user_func_array($function, $args);
					return;	
			}			
		}
	}

}