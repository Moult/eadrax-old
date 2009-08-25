<?php defined('SYSPATH') or die('No direct script access.');

/*======================================

Allow quick entries of group items with non-latin labels

======================================*/

class Formo_group_alias {

	public static $dividers = array('|', ',');
	public $form;

	public function __construct( & $form)
	{
		Event::add('formogroup.preload', array($this, 'add'));
		$this->form = $form;
	}
	
	public static function load( & $form)
	{
		return new Formo_group_alias($form);
	}
	
	public function add()
	{
		$data =& Event::$data;
		
		foreach ($data['values'] as $key => $value)
		{
			if ( ! preg_match('/::/', $value))
				continue;
			
			list($_name, $_value) = explode('::', $value);
			
			$data['values'][$key] = array
			(
				'name'	=> $_name,
				'label'	=> $_value
			);
			
		}
	}

}