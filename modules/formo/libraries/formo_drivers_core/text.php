<?php defined('SYSPATH') or die('No direct script access.');

class Formo_text_Driver extends Formo_Element {
	
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
	}
	
	public function render()
	{
		return '<input type="text" name="'.$this->name.'" value="'.htmlspecialchars($this->value).'"'.Formo::quicktagss($this->_find_tags()).' />';
	}
	
}