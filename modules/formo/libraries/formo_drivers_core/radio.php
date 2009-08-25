<?php defined('SYSPATH') or die('No direct script access.');

class Formo_radio_Driver extends Formo_Element {

	public $checked;
	public $values = array();

	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);

		Formo::instance($this->formo_name)
			->add_function('check', array($this, 'check'))
			->add_function('uncheck', array($this, 'uncheck'));
	}
	
	public function render()
	{
		$checked = ($this->checked === TRUE) ? ' checked="checked"' : '';
		return '<input type="radio" value="'.htmlspecialchars($this->value).'" name="'.$this->name.'"'.$checked.Formo::quicktagss($this->_find_tags()).' />';
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