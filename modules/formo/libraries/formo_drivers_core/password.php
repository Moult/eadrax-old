<?php defined('SYSPATH') or die('No direct script access.');

class Formo_password_Driver extends Formo_Element {

	public $keep = FALSE;
	
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
	}
	
	public function render()
	{
		$value = ($this->keep === TRUE) ? ' value="'.$this->value.'"' : '';
		return '<input type="password" name="'.$this->name.'"'.$value.Formo::quicktagss($this->_find_tags()).' />';
	}

}