<?php defined('SYSPATH') or die('No direct script access.');

class Formo_space_Driver extends Formo_Element {
		
	public function __construct($name='',$info=array())
	{
		$this->name = $name;
		$this->value = '<div style="height:'.$info['value'].'"></div>';
	}
	
	public function render()
	{
		return $this->value;
	}

	protected function build()
	{
		return "\t".$this->render()."\n";
	}
	
	public function add_post()
	{
		return;
	}
		
	public function get_value()
	{
		return FALSE;
	}

}