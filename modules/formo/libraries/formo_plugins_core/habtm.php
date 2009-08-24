<?php defined('SYSPATH') or die('No direct script access.');

/*=======================================

Formo Plugin HABTM

Must be loaded instead of orm plugin (this extends it)

=======================================*/

Formo::include_file('plugin', 'orm');

class Formo_habtm extends Formo_orm {

	protected $elements = array();
	
	protected $habtm_model = array();
	protected $habtm_plural = array();
	protected $habtm_table = array();
	protected $habtm_name = array();
	
	public function __construct( & $form)
	{
		parent::__construct($form);
								
		$this->form
			->add_function('habtm', array($this, 'habtm'));
	}
	
	public static function load( & $form)
	{
		return new Formo_habtm($form);
	}
			
	public function habtm($model = NULL, $table = NULL, $id = NULL)
	{	
		if ( ! $id AND ! $table)
		{
			$table = $model;
			$model = NULL;
		}
		
		if ( ! empty($this->form->model[$model]))
		{
			$model = $this->form->model[$model];
		}
		elseif ($id)
		{
			$model = ORM::factory($model, $id);
			$this->form->model[$model->object_name] = $model;
		}
		elseif ( ! is_object($model))
		{
			// if no model is specified, use the last loaded model
			foreach ($this->form->model as $key => $_model)
			{
				$model = $this->form->model[$key];
				$table = $table;
				break;
			}
		}

		$model_name = $model->object_name;
		$keyname = "$model_name.$table";
		
		$this->habtm_name[$keyname] = ORM::factory($table)->primary_val;
		$this->habtm_plural[$keyname] = inflector::plural($table);
		$this->habtm_table[$keyname] = $table;
		$this->habtm_model[$keyname] = $model;
		
		$values = array();
		foreach (ORM::factory($this->habtm_table[$keyname])->find_all() as $option)
		{
			$values[$option->id] = $option->{$this->habtm_name[$keyname]};
			$this->elements[$keyname][] = $option->{$this->habtm_name[$keyname]};
		}		
		
		$this->form->add_group($this->habtm_table[$keyname].'[]', $values)->set($this->habtm_table[$keyname], 'required', FALSE);
		
		$this->fill_initial_values($keyname);
	}
	
	protected function fill_initial_values($keyname)
	{
		foreach ($this->habtm_model[$keyname]->{$this->habtm_plural[$keyname]} as $checked)
		{
			$el_name = strtolower(str_replace(' ', '_', $checked->{$this->habtm_name[$keyname]}));
			$this->form->check($this->habtm_table[$keyname], $el_name);
		}	
	}
	
	public function save($name = '')
	{
		parent::save($name);
		$this->habtm_save();
	}

	public function auto_save()
	{
		parent::auto_save();
		if ( ! $this->form->_error AND $this->auto_save === TRUE AND $this->habtm_model)
		{
			$this->habtm_save();
		}
	}
	
	protected function habtm_save($keyname = '')
	{
		if ( ! empty($keyname))
		{
			foreach ($this->elements[$keyname] as $element)
			{
				$_element = strtolower(str_replace(' ', '_', $element));
				if ($this->form->{$this->habtm_table[$keyname]}->$_element->checked === TRUE)
				{
					$this->habtm_model[$keyname]->add(ORM::factory($this->habtm_table[$keyname])->where($this->habtm_name[$keyname], $element)->find());
				}
				else
				{
					$this->habtm_model[$keyname]->remove(ORM::factory($this->habtm_table[$keyname])->where($this->habtm_name[$keyname], $element)->find());
				}
			}
			
			return $this->habtm_model[$keyname]->save();	

		}

		foreach ($this->elements as $keyname => $elements)
		{
			$this->habtm_save($keyname);
		}

	}

}