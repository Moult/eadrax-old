<?php defined('SYSPATH') or die('No direct script access.');

class Formo_checkbox_Driver extends Formo_Element {

	public $checked;
	public $values = array();
	
	protected $skip_check = FALSE;

	public function __construct($name='',$info=array())
	{
		parent::__construct($name,$info);
		
		if ( ! $this->value)
		{
			$this->value = $name;
		}
				
		Formo::instance($this->formo_name)
			->add_function('check', array($this, 'check'))
			->add_function('uncheck', array($this, 'uncheck'));
	}
	
	public function render()
	{
		$checked = ($this->checked === TRUE) ? ' checked="checked"' : '';
		return '<input type="checkbox" value="'.$this->value.'" name="'.$this->name.'"'.$checked.Formo::quicktagss($this->_find_tags()).' />';
	}
	
	protected function validate_this()
	{
		$this->was_validated = TRUE;
		if ($this->required AND ! Input::instance()->post($this->name))
		{
			$this->error = $this->required_msg;
			return $this->error;
		}
	}
	
	public function add_post($value)
	{
		if ($this->skip_check === TRUE)
			return;
			
		$this->checked = ($value AND $value == $this->value) ? TRUE : FALSE;
	}
	
	public function check($group='', $element='')
	{
		$form = Formo::instance($this->formo_name);
		
		if ( ! $group AND ! $element)
		{
			$this->checked = TRUE;
		}
		elseif (is_object($form->$group) AND get_class($form->$group) == 'Formo_Group')
		{
			foreach (Formo::splitby($element) as $el)
			{
				$form->$group->check($el);
			}
		}
		else
		{
			$form->$group->check();
		}		
	}
	
	public function get_value()
	{
		return ($this->checked) ? $this->value : FALSE;
	}

	public function uncheck()
	{
		$this->checked = FALSE;
		$this->skip_check = TRUE;
	}

	public function clear()
	{
		$this->checked = FALSE;
	}

}