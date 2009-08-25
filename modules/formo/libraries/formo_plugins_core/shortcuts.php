<?php defined('SYSPATH') or die('No direct script access.');

/*=====================================

Formo Plugin Orm

Adds a bunch of shortcut methods to

Formo Functions Added:
	ta  - textarea
	ra  - radio
	g    - group
	cb  - checkbox
	sel - select
	hid - hidden
	sub - submit
	but - button
	txt - text
	pass - password
	bool - bool
	cap - captcha

=====================================*/


class Formo_shortcuts {

	protected $form;
	protected static $shortcuts = array
	(
		'ta'	=> 'textarea',
		'ra'	=> 'radio',
		'cb'	=> 'checkbox',
		'g'		=> 'group',
		'sel'	=> 'select',
		'img'	=> 'image',
		'html'	=> 'html',
		'but'	=> 'button',
		'hid'	=> 'hidden',
		'sub'	=> 'submit',
		'pass'	=> 'password',
		'txt'	=> 'text',
		'bool'	=> 'bool',
		'cap'	=> 'captcha',
		'file'	=> 'file',
	);

	public function __construct( & $form)
	{
		$this->form = $form;
		foreach (self::$shortcuts as $short => $long)
		{
			$form->add_function($short, array($this, $long));
		}
	}

	public static function load( & $form)
	{
		return new Formo_shortcuts($form);
	}
		
	// =======================================================

	public function __call($method, $values)
	{
		$values[1] = ( ! isset($values[1])) ? array() : $values[1];
		$this->form->add($method, $values[0], $values[1]);
	}	
		
	public function select($name, $values=array(), $info=array())
	{
		$info = Formo::quicktags($info);
		$info['values'] = $values;
		$this->form->add('select', $name, $info);
	}
			
	public function group($name, $values, $info=NULL)
	{
		$this->form->add_group($name, $values, $info);
	}
	
	public function image($name, $src, $info=array())
	{
		$info = Formo::quicktags($info);
		$info['src'] = $src;
		$this->form->add('image',$name,$info);	
	}
	
	public function button($name, $value, $info=array())
	{
		$info = Formo::quicktags($info);
		$info['value'] = $value;
		$this->form->add('button', $value, $info);	
	}
	
	public function html($name, $value)
	{
		$this->form->add('html', $name, $value);
	}
		
}