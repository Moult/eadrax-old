<?php defined('SYSPATH') or die('No direct script access.');

class Formo_image_Driver extends Formo_Element {
	
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
		
		$this->value = $name;
	}
	
	public static function shortcut($defs, $name, $src, $info = array())
	{
		$info = self::process_info($defs, $info);
		$info['src'] = $info;
		
		return new Formo_image_Driver($name, $info);
	}
	
	public function render()
	{
		return '<input type="image" name="'.$this->name.'" value="'.htmlspecialchars($this->value).'"'.Formo::quicktagss($this->_find_tags()).' />';
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