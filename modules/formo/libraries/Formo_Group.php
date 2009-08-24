<?php defined('SYSPATH') or die('No direct script access.');

/*
	Version 1.1.6
	avanthill.com/formo_manual/
	
	Requires Formo and Formo_Group
	Kohana 2.2
*/

class Formo_Group_Core {

	public $name;
	public $label;
	public $name_is_array = FALSE;
	public $type = 'checkbox_group';
	public $el_type = 'checkbox';
	public $elements = array();
	
	public $label_class = 'group';
	
	public $open = "<p>";
	public $close = "</p>";
	public $group_open = "<span>";
	public $group_close = "</span>";
	public $class;
	
	public $element_label = 'close';
	
	public $required = TRUE;
	public $value;
	
	public $rule;
	public $post_type;
	
	public $error;
	public $required_msg = 'Required';
	public $error_msg = 'Invalid';
	public $error_open = '<span class="{error_msg_class}">';
	public $error_close = '</span>';
	public $error_class = 'errorInputGroup';
	public $error_msg_class = 'errorMessage';
	
	private $_elements_string;

	/**
	 * Does all the necessary creating
	 *
	 */	
	public function __construct($name, $values, $info=array())
	{
		$data = array('object'=>$this, 'name' => $name, 'values' => $values, 'info' => $info);
		Event::run('formogroup.preload', $data);
		
		$type = (preg_match('/\[]/', $data['name'])) ? 'checkbox' : 'radio';
		
		$this->type = $type.'_group';
		$this->el_type = $type;
		$this->name = strtolower(preg_replace('/\[\]/', '', $data['name']));
		$this->name = str_replace(' ', '_', $this->name);
		$this->name_is_array = (preg_match('/\[\]/', $data['name'])) ? TRUE : FALSE;
		$this->label = $this->name;
				
		($this->el_type == 'checkbox' and $this->value = array());
		
		$this->_add_values($data['values'], $data['info']);

		Event::run('formogroup.add', $this);
	}
	
	private function _add_values($values, $original_info)
	{
		foreach ($values as $key=>$value)
		{
			$info = Formo::quicktags($original_info);
			
			$el_name = $value;
			$el_value = $key;
						
			if (is_array($value))
			{
				$el_name =  $value['name'];
				foreach ($value as $_key => $_value)
				{
					$info[$_key] = $_value;
				}
				unset($info['name']);
			}

			$info['id'] = $this->name.'_'.$key;
			
			foreach ($info as $info_key=>$info_value)
			{
				if ( ! isset($this->$info_key))
					continue;
					
				$this->$info_key = $info_value;
			}
			
			$this->add($this->el_type, $el_name, $el_value, $info);	
		}
	}

	/**
	 * Factory method. Returns a new Element_Group object
	 *
	 * @return object
	 */
	public static function factory($type, $name, $values, $info=array())
	{
		return new Formo_Group($type, $name, $values, $info);
	}

	/**
	 * Magic __set method. Keeps track of elements added to object
	 *
	 */
	public function __set($var, $val)
	{
		if (is_object($val) AND is_object($val))
		{
			$this->elements[$var] = $val->value;
		}
		$this->$var = $val;
	}
	
	// makes attribute equal to value[0]
	public function __call($function ,$values)
	{
		$this->$function = $values[0];
		
		return $this;
	}	
	
	/**
	 * Magic __toString method. Returns all formatted elements without label
	 * or open and close.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return $this->get(TRUE);
	}
	
	public function add($type, $name, $value, $info)
	{
		$info['value'] = (empty($info['value'])) ? $value : $info['value'];
		$info['label'] = (empty($info['label'])) ? preg_replace('/\[[a-zA-Z0-9_ ]*\]/','', $name) : $info['label'];

		$element_name = ($this->name_is_array) ? $this->name.'[]' : $this->name;
				
		if ($this->name_is_array AND preg_match('/\[([a-zA-Z0-9_ ]+)\]/', $name, $matches))
		{
			$element_name = $this->name.'['.$matches[1].']';
		}
		
		Formo::include_file('driver', $type);
		
		$class = 'Formo_'.$type.'_Driver';
		$this->{strtolower(str_replace(' ', '_', $name))} = new $class($element_name, $info);
	}
	
	public function add_post($value, $type)
	{
		$this->post_type = $type;
		switch ($this->el_type)
		{
			case 'checkbox':
				$this->_find_checked_checkbox();
				break;
			case 'radio':
				$this->_find_checked_radio();
				break;
		}
	}
	
	public function filter_label($filter)
	{
		$this->label = call_user_func($filter, $this->label);
		foreach ($this->elements as $k => $v)
		{
			$this->$k->filter_label($filter);
		}
	}
	
	public function check($element=NULL)
	{
		if ($element AND $this->el_type == 'radio')
		{
			$this->$element->check();
			$this->value = $this->$element->value;
		}
		elseif ($element)
		{
			$this->$element->check();
			$key = (preg_match('/\[([a-zA-Z0-9_ ]+)\]/', $this->$element->name, $matches))
			     ? $matches[1]
			     : $element;
			
			$this->value[$key] = $this->$element->get_value();
		}
		else
		{
			foreach ($this->elements as $element=>$value)
			{
				$this->check($element);
			}
		}
	}
	
	private function _find_checked_checkbox()
	{
		foreach ($this->elements as $k=>$v)
		{
			$type = $this->post_type;
			$post = Input::instance()->$type($this->name);
			if (preg_match('/\[(.+)?\]/', $k, $matches))
			{
				$post_key = $matches[1];
				if (isset($post[$post_key]) AND $post[$post_key] == $v)
				{
					$this->check($k);
				}
			}
			elseif (is_array($post) AND in_array($v, $post))
			{
				$this->check($k);
			}
			else
			{
				$this->$k->uncheck();
			}
		}		
	}
		
	private function _find_checked_radio()
	{
		foreach ($this->elements as $k=>$v)
		{
			$type = $this->post_type;
			$post = Input::instance()->$type($this->name);
			if (Formo::has_value($post) AND $post == $v)
			{
				$this->check($k);
				$this->value = $post;
			}
		}
	}

	/**
	 * validate method. run validation through all elements in group
	 *
	 */	
	function validate()
	{
		Event::run('formogroup.pre_validate', $this);

		foreach ($this->elements as $element=>$value)
		{
			$this->$element->validate();
		}
		
		if ($this->required AND ! Formo::has_value(Input::instance()->post($this->name)))
		{
			$this->error = $this->required_msg;
			$this->add_class($this->error_class);
			return $this->error;
		}
		elseif ($this->rule)
		{
			$rules = ( ! is_array($this->rule)) ? array($this->rule) : $this->rule;
			foreach ($rules as $rule)
			{
				$function = (isset($rule['rule'])) ? $rule['rule'] : $rule;
				$error_msg = (isset($rule['error_msg'])) ? $rule['error_msg'] : $this->error_msg;
				$type = $this->post_type;

				if ( ! call_user_func($function, Input::instance()->$type($this->name)))
				{
					$this->error = $error_msg;
					$this->add_class($this->error_class);
					return $this->error;
				}
			}
		}

		Event::run('formogroup.post_validate', $this);
	}
	
	public function append_errors()
	{
		if ($this->error)
		{
			$this->show_error = $this->error;
			$this->add_class($this->error_class);
		}
	}	

	/**
	 * add_class method. add class to class
	 *
	 */		
	public function add_class($class)
	{
		$this->class = ($this->class) ? $this->class.' '.$class : $class;
	}
	
	/**
	 * _build_error method. turn error into formatted text
	 *
	 */	
	private function error()
	{
		if ($this->error)
		{
			$search = '/{error_msg_class}/';
			$replace = $this->error_msg_class;
			
			return preg_replace($search, $replace, $this->error_open)
			      .$this->error
			      .$this->error_close;
			
			$message = preg_replace('/{error_msg_class}/', $this->error_msg_class, $this->open);
			$message.= $this->error_msg;
			$message.= $this->error_close;
			return $message;
		}
	}
	
	/**
	 * add_rule method. Adds a rule to the object
	 *
	 * @return  object
	 */		
	public function add_rule($rule, $error_msg='')
	{
		if ($rule == 'required')
		{
			$this->required = TRUE;
			$this->required_msg = ($error_msg) ? $error_msg : $this->required_msg;
		}
		
		if ($this->rule) Formo::into_array($this->rule);
		if (is_array($rule))
		{
			$this->rule = array_merge($this->rule,$rule);
		}
		else
		{
			$error_msg = ($error_msg) ? $error_msg : $this->error_msg;
			$this->rule[] = array('rule'=>$rule, 'error_msg'=>$error_msg);
		}
	}

	/**
	 * _build_group_open method. turn open tag into formatted text
	 * or open and close.
	 *
	 * @return string
	 */	
	private function _build_group_open()
	{
		$search = '/{class}/';
		$replace = ' class="'.$this->class.'"';
		if ($this->class)
		{
			$this->group_open = preg_replace($search, $replace, $this->group_open);
		}
		
		return $this->group_open;
	}
	
	public function get_value()
	{
		return $this->value;
	}
	
	
	public function clear()
	{
		foreach ($this->elements as $element=>$type)
		{
			$this->$element->clear();
		}
	}

	/**
	 * get method. turns all elements in group into formatted elements
	 *
	 * @return  string
	 */			
	public function get($inside_only = FALSE)
	{
		Event::run('formogroup.pre_render', $this);

		foreach ($this->elements as $element=>$type)
		{
			$label_place = ($this->element_label == 'open') ? 'element_open' : 'element_close';
			
			$search = array
			(
				'/\[[a-zA-Z0-9_ {}]*\]/',
			);
			
			$label = preg_replace($search, '', $this->$element->label);
			
			$this->$element->$label_place = '<label for="'.$this->$element->id.'" class="'.$this->label_class.'">'.$label.'</label>';
			
			$this->_elements_string.= $this->$element->get(TRUE)."\n";
		}
		
		$inside = $this->_build_group_open()."\n"
			  .$this->_elements_string
			  .$this->group_close."\n";
			  
		if ( ! $inside_only)
		{
			$inside = $this->open."\n"
					. '<label for="'.$this->label.'">'.$this->label.':</label>'."\n"
					. $inside
					. $this->error()
					. $this->close."\n";
		}

		return $inside;

		Event::run('formogroup.post_render', $this);
	}
	
}