<?php defined('SYSPATH') or die('No direct script access.');

class Formo_hidden_Driver extends Formo_Element {
		
	public function __construct($name='',$info=array())
	{
		parent::__construct($name,$info);
	}
	
	public static function shortcut($defs, $name, $value='', $info='')
	{
		$info = self::process_info($defs, $info);
		$info['value'] = $value;
		
		return new Formo_hidden_Driver($name, $info);
	}
	
	public function render()
	{
		return '<input type="hidden" name="'.$this->name.'" value="'.htmlspecialchars($this->value).'"'.Formo::quicktagss($this->_find_tags()).' />';
	}
	
	protected function build()
	{
		return "\t".'<div>'.$this->render().'</div>'."\n";
	}

	public function clear()
	{
		return;
	}

}