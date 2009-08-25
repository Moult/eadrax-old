<?php defined('SYSPATH') or die('No direct script access.');

class Formo_h_Driver extends Formo_Element {

	public $tags;

	public function __construct($name, $info=array())
	{
		parent::__construct($name, $info);
	}

	public static function shortcut($defs, $value, $tag = 'h3', $props = NULL)
	{
		$name = strtolower(str_replace(' ', '_', $value));
		
		$info = self::process_info($defs, array(), $name);
		
		$info['tags'] = array('<'.$tag.Formo::quicktagss($props).'>', '</'.$tag.'>');
		$info['value'] = $value;
		
		return New Formo_h_Driver($name, $info);
	}
	
	public function render()
	{
		return $this->tags[0].$this->value.$this->tags[1];
	}
	
	public function build()
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