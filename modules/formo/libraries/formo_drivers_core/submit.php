<?php defined('SYSPATH') or die('No direct script access.');

class Formo_submit_Driver extends Formo_Element {
		
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
		
		if ( ! $this->value)
		{
			$this->value = $name;
		}

	}
	
	public function render()
	{
		return '<input type="submit" name="'.preg_replace('/ /','_',$this->name).'" value="'.htmlspecialchars($this->value).'"'.Formo::quicktagss($this->_find_tags()).' />';
	}
	
	protected function build()
	{
		return "\t".$this->open
			  .$this->element()
			  .$this->close."\n";
	}
	
	public function add_post()
	{
		return;
	}
	
	public function validate_this()
	{
		return;
	}
	
	public function get_value()
	{
		return FALSE;
	}
	
	public function clear()
	{
		return;
	}

}