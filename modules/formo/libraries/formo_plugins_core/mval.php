<?php defined('SYSPATH') or die('No direct script access.');

/*====================================

Mval Plugin

Use Models to validate

====================================*/

class Formo_mval {

	public static $form;
	
	public static $mval_model;
	public $models = array();
	public static $data = array();
	
	
	public function __construct( & $form)
	{
		Event::add('formo.post_validate', array($this, 'validate'));

		// reference the form object
		self::$form = $form;
				
		// attach functions
		self::$form
			->add_function('mval', array($this, 'mval'))
			->add_function('model', array($this, 'model'));
			
	}
	
	public static function load( & $form)
	{
		return new Formo_mval($form);
	}
	
	public function mval($model, $method = 'mval', $construct_data = NULL)
	{
		$this->_load_model($model, $construct_data)->$method(self::$form);
	}

	protected function _load_model($model, $construct_data)
	{
		if (is_object($model))
		{
			self::$mval_model = $model;
		}
		elseif (isset(self::$form->model[$model]) AND is_object(self::$form->model[$model]))
		{
			self::$mval_model = self::$form->model[$model];
		}
		else
		{
			$model_name = ucfirst($model).'_Model';
			self::$mval_model = new $model_name($construct_data);
		}
		
		return self::$mval_model;
	}
	
	public function model($model, $method, $data = array(), $construct_data = NULL)
	{
		$this->models[] = array('model'=>$model, 'method'=>$method, 'data'=>Formo::splitby($data), 'construct_data'=>$construct_data);
	}
	

	public function validate()
	{
		if ( ! self::$form->_validated)
			return;
		
		foreach ($this->models as $values)
		{
			if ($values['data'])
			{
				foreach ($values['data'] as $element)
				{
					$data[$element] = self::$form->$element->get_value();
				}
			}
			else
			{
				$data = self::$form->get_values();
			}
			
			self::$data = $data;
						
			$this->_load_model($values['model'], $values['construct_data'])->{$values['method']}();
			
		}
		
		foreach (self::$form->find_elements() as $element)
		{
			if (self::$form->$element->error)
			{
				self::$form->set('error', TRUE);
				self::$form->set('validated', FALSE);
			}
		}
	}		

}