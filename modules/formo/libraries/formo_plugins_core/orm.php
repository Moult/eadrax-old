<?php defined('SYSPATH') or die('No direct script access.');

/*=====================================

Formo Plugin Orm

Auto-generate a form using orm models

=====================================*/


class Formo_orm {

	protected $form;

	public $model = array();
	public $model_array = array();
	public $ignores = array();
	public $auto_save = FALSE;
	public $aliases = array();
	
	protected $save_id = array();
	
	public function __construct( & $form)
	{
		Event::add('formo.pre_addpost', array($this, 'pre_addpost'));
		Event::add('formo.post_addpost', array($this, 'post_addpost'));
		Event::add('formo.post_validate', array($this, 'auto_save'));
		Event::add('formo.pre_render', array($this, 'check_cleared'));
		
		$this->form = $form;		
		$this->form
			->add_function('orm', array($this, 'orm'))
			->add_function('save', array($this, 'save'))
			->add_function('get_model', array($this, 'get_model'))
			->bind('ignores', $this->ignores)
			->bind('aliases', $this->aliases)
			->bind('auto_save', $this->auto_save)
			->bind('model', $this->model);
	}
	
	public static function load( & $form)
	{
		return new Formo_orm($form);
	}
	
	public static function get_model_name($model)
	{
		$_model = preg_split('/_Model$/', get_class($model));
		
		return strtolower($_model[0]);
	}
	
	public function orm($_model, $id=0)
	{
		if ( ! is_object($_model))
		{
			// check to see if the form has cleared
			// if not, check to see if we just entered a new record
			if ( ! Session::instance()->get('__formo_'.$this->form->_formo_name.'_cleared')
			     AND $_id = Session::instance()->get('__formo_model_'.$_model))
			{
				$this->save_id[$_model] = $_id;
			}
			
			$id = ( ! empty($_id)) ? $_id : $id;
			$this->model[$_model] = ORM::factory($_model, $id);
		}
		// if the model specified is an object, go ahead and use it
		else
		{
			$model_name = self::get_model_name($_model);
			$this->model[$model_name] = $_model;
			$_model = $model_name;
		}
		
		$this->model_array[$_model] = array_keys($this->model[$_model]->as_array());
		
		$this->load_settings($_model);
		$this->load_elements($_model);								
	}
		
	private function load_settings($_model)
	{
		$straight_settings = array
		(
			'auto_save',
			'habtm'
		);
		
		$settings = array
		(
			'formo_ignores'			=> 'ignores',
			'formo_globals'			=> 'globals',
			'formo_defaults'		=> 'defaults',
			'formo_rules'			=> 'auto_rules',
			'formo_label_filters'	=> 'label_filters',
			'formo_order'			=> 'order',
			'formo_pre_filters'		=> 'pre_filters'
		);
		
		// first let's set auto save to auto_save, same for habtm
		foreach ($straight_settings as $var)
		{
			$model_var = 'formo_'.$var;
			if (isset($this->model[$_model]->$model_var))
			{
				$this->$var = $this->model[$_model]->$model_var;
			}
		}
		
		// now we'll go through the other settings
		foreach ($settings as $orm_name=>$name)
		{
			if ( ! empty($this->model[$_model]->$orm_name))
			{
				$formo_val = (isset($this->form->$name)) ? $name : '_'.$name;
				$this->form->$formo_val = array_merge($this->form->$formo_val, $this->model[$_model]->$orm_name);
			}
		}
		
		// finally we need to do something a bit different for aliases	
		if ( ! empty ($this->model[$_model]->formo_aliases))
		{
			$this->aliases[$_model] = (isset($this->aliases[$_model]))
									? array_merge($this->aliases[$_model], $this->model[$_model]->formo_aliases)
									: $this->model[$_model]->formo_aliases;
		}		
		
	}
	
	private function load_elements($_model)
	{
		foreach ($this->model[$_model]->table_columns as $field => $value)
		{
			$alias_field = $field;
			if (isset($this->form->aliases[$_model][$field]))
			{
				$alias_field = $this->form->aliases[$_model][$field];
			}
						
			if (in_array($field, $this->form->ignores))
				continue;
			
			// relational tables
			$fkey = preg_replace('/_id/','',$field);
			
			if (in_array($fkey, $this->model[$_model]->belongs_to) OR in_array($fkey, $this->model[$_model]->has_one)
			OR array_key_exists($fkey, $this->model[$_model]->belongs_to) OR array_key_exists($fkey, $this->model[$_model]->has_one))
			{
				$values = array('_blank_'=> '');				
				$modeler = $this->model[$_model]->$fkey->find_all();
				
				foreach ($modeler as $value)
				{
					$primary_val = $value->primary_val;
					$primary_key = $value->primary_key;
					$values[$value->$primary_val] = $value->$primary_key;
				}
				$this->form->add_select($alias_field,$values,array('value'=>$this->model[$_model]->$field));
			}
			else
			{
				$this->form->add($alias_field, array('value'=>$this->model[$_model]->$field));
			}
		}
	}
			
	public function pre_addpost()
	{
		if ($this->form->_post_added)
			return;
			
		if ($this->form->_cleared)
		{
			$this->form->_post_added = TRUE;
		}
	}
	
    public function post_addpost()
    {
        if ( ! $this->form->_post_type)
            return;

        $type = $this->form->_post_type;

        $post = Input::instance();
        foreach ($this->model_array as $model=>$array)
        {
            foreach ($array as $model_field)
            {
            	// the form field may be different than the model field (aliases)
                $form_field = $model_field;
                // Check if there are aliases associated with this model
                if ( ! empty($this->aliases[$model]))
                {
                	// If if this is an aliase, make the form field match the alias
                    $form_field = (array_key_exists($model_field, $this->aliases[$model]))
                                 ? $this->aliases[$model][$model_field]
                                 : $model_field;
                }
				
				// if the element doesn't exist, continue
                if ( ! isset($this->form->$form_field))
                    continue;

                if ($this->form->$form_field->type == 'bool')
                {
                	// if it's a bool handle as a bool
                    $_type = ($type == 'post') ? $_POST : $_GET;
                    $this->model[$model]->$model_field
                        = (isset($_type[$model_field]))
                        ? 1
                        : 0;
                }
                else
                {
                	// set the model value equal to the form field value
                    $this->model[$model]->$model_field = $post->$type($form_field);
                }
            }
        }        
    }
		
	public function save($name = '')
	{
		Event::run('formo.orm_pre_save');
		
		if ($name)
		{
			$add_id = ( ! empty($this->save_id[$name]) OR ! $this->model[$name]->id) ? TRUE : FALSE;
			$this->model[$name]->save();
			($add_id AND $this->add_id($name, $this->model[$name]->id));
		}
		else
		{
			foreach ($this->model as $name=>$model)
			{
				$this->save($name);
			}
		}
		
		Event::run('formo.orm_post_save');
	}
	
	public function get_model($name = NULL)
	{
		return ($name) ? $this->model[$name] : $this->model;
	}
	
	public function auto_save()
	{
		// auto-save ORM models
		if ( ! $this->form->_error AND $this->auto_save === TRUE AND $this->model)
		{
			$this->save();
		}	
	}
	
	protected function add_id($name, $id)
	{
		Session::instance()->set_flash('__formo_model_'.$name, $id);
	}
	
	public function check_cleared()
	{
		if ($this->form->_cleared === TRUE)
		{
			foreach ($this->model as $name=>$model)
			{
				$this->form->remove('__model_'.$name);
				$model->clear();
			}
		}
	}	

}