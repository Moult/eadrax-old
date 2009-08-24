<?php defined('SYSPATH') or die('No direct script access.');

class Formo_bool_Driver extends Formo_Element {

	public $checked;

	public function __construct($name='',$info=array())
	{
		parent::__construct($name,$info);
		
		$this->required = FALSE;
		if ($this->value == 1)
		{
			$this->checked = TRUE;
		}
	}
	
	public function render()
	{
		$checked = ($this->checked === TRUE) ? ' checked="checked"' : '';
		return '<input type="checkbox" value="'.$this->value .'" name="'.$this->name.'"'.$checked.Formo::quicktagss($this->_find_tags()).' />';
	}

	public function build()
	{
		return "\t".$this->open
			  .$this->label()
			  .$this->element()
			  .$this->error()
			  .$this->close."\n";
	}
		
	public function add_post($value, $type)
	{
		$_type = ($type == 'post') ? $_POST : $_GET;
		
		if (isset($_type[$this->name]))
		{
			$this->checked = TRUE;
			$this->value = 1;
		}
		else
		{
			$this->checked = FALSE;
			$this->value = 0;
		}
	}

	public function clear()
	{
		$this->checked = FALSE;
	}

}