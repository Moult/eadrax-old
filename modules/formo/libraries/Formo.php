<?php defined('SYSPATH') or die('No direct script access.');

/* 
	Version 1.1.6
	avanthill.com/formo_manual/
		
	Requires Formo_Element and Formo_Group
	Kohana 2.2
*/

class Formo_Core {

	protected static $_instance = array();
	
	public $_formo_name;
	public $_formo_type;
	
	public $_error;
	public $_sent = FALSE;
	public $_elements = array();
	public static $last_accessed;
			
	public $_functions = array();
	protected static $__includes = array();
			
	public $_post_added;
	public $_was_validated;
	public $_validated;
	public $_post_type;
	public $_cleared;
			
	public $_open = '<form action="{action}" method="{method}" class="{class}" name="{name}">';
	public $_close = '</form>';
	public $_action;
	public $_method = 'post';
	public $_class = 'standardform';
			
	public $_order = array();
	
	public $_auto_rules = array();
	public $_pre_filters = array();
	public $_post_filters = array();
	public $_label_filters = array();
						
	public $_globals = array();
	public $_defaults = array();
	
	public $_return;
			
	public function __construct($name='formo',$type='')
	{
		$data = array('name'=>$name, 'type'=>$type);
		Event::run('formo.pre_construct', $data);
		
		$this->_formo_name = ($data['name']) ? $data['name'] : 'formo';
		$this->_formo_type = $data['type'];
		$this->add('hidden','__formo',array('value'=>$this->_formo_name));
		
		$this->_compile_plugins();
		$this->_compile_settings('form_globals');
		$this->_compile_settings('globals');
		$this->_compile_settings('defaults');
		$this->_compile_settings('auto_rules');
		$this->_compile_settings('label_filters');
		$this->_compile_settings('pre_filters');
		$this->_compile_settings('post_filters');

		self::$_instance[$this->_formo_name] = $this;
		
		Event::run('formo.post_construct');
	}
		
	public function bind($property, & $value)
	{
		// only bind the property if it doesn't already exist
		if (empty($this->$property))
		{
			$this->$property =& $value;
		}
		else
		{
			$value = $this->$property;
			$this->$property =& $value;
		}
				
		return $this;
	}
	
	public function add_function($function, $values)
	{
		$this->_functions[$function] = $values;
				
		return $this;
	}
		
	public function plugin($plugin)
	{
		$plugins = (func_num_args() > 1) ? func_get_args() : self::splitby($plugin);
		
		foreach ($plugins as $name)
		{
			self::include_file('plugin', $name);
			call_user_func('Formo_'.$name.'::load', $this);
		}

		return $this;
	}
	
	/**
	 * Magic __call method. Handles element-specific stuff and
	 * set_thing and add_thing
	 *
	 * @return  object
	 */		
	public function __call($function, $values)
	{
		if ( ! empty($this->_functions[$function]))
		{
			$return = call_user_func_array($this->_functions[$function], $values);
			
			return ($return) ? $return : $this;
		}
				
		$element = ( ! empty($values[0])) ? $values[0] : NULL;
		$formo_var = '_'.$function;
		
		// if the first value is an element and the method exists in the element, run that method
		if ( ! is_array($element) AND isset($this->$element)
		    AND ! isset($this->$element->$function)
		    AND method_exists($this->$element, $function))
		{
			unset($values[0]);
			call_user_func_array(array($this->$element, $function), $values);
			return $this;			
		}
		// if it's element(), return the value
		elseif (isset($this->$function) AND ! $values)
		{
			$this->validate();
			return $this->$function->value;
		}
		// if it is an element and the property exists, set it
		elseif (isset($this->$function) AND isset($values[1]))
		{
			$this->$function->$element($values[1]);
			return $this;
		}
		// if it's a property and the element and property are defined
		elseif (isset($values[1]) AND isset($this->$element->$function))
		{
			$this->$element->$function($values[1]);
		}
		// if it is an element and no property is defined, it's value
		elseif (isset($this->$function))
		{
			$this->$function->value = $element;
			return $this;
		}
		// simply set the variable to something
		elseif (isset($this->$formo_var))
		{
			if (is_array($values[0]) AND isset($values[1]) AND is_array($values[1]))
			{
				$this->$formo_var = $values;
			}
			elseif (is_array($values[0]))
			{
				$this->$formo_var = $values[0];
			}
			else
			{
				$this->$formo_var = $values;
			}
		}
		// run an element shortcut method
		elseif (substr($function, 0, 4) == 'add_')
		{
			$type = substr($function, 4);
			$defs = array
			(
				'formo_name'	=> $this->_formo_name,
				'type'			=> $type,
				'name'			=> $values[0]
			);
			
			array_unshift($values, $defs);
			$driver = 'Formo_'.$defs['type'].'_Driver';
			
			self::include_file('driver', $defs['type']);

			$el = call_user_func_array($driver.'::shortcut', $values);
			
			$this->{$el->name} = $el;
			self::$last_accessed = $el->name;
		}
		// if all else fails, let's just use the last accessed object
		elseif (self::$last_accessed)
		{
			$this->{self::$last_accessed}->$function($element);
		}
		
		return $this;
	}
	
	/**
	 * Magic __set method. Keeps track of elements added to form object
	 *
	 */				
	public function __set($var, $val)
	{
		// if it's an object (element, element group), add it to the elements
		if (is_object($val))
		{
			$this->$var = $val;
			$this->_elements[$var] = $this->$var->type;
		}
		// if it's a formo value, properly set it
		elseif ($formo_var = '_'.$var AND isset($this->$formo_var))
		{
			$this->$formo_var = $val;
		}
		else
		{
			$this->$var = $val;
		}		
	}
			
	public function __toString()
	{
		return $this->get();	
	}

	/**
	 * factory method. Creates and returns a new form object
	 *
	 * @return  object
	 */			
	public static function factory($name='formo',$type='')
	{	
		return new Formo($name, $type);
	}
	
	public static function instance($name='formo')
	{
		$name = ($name) ? $name : 'formo';
		return (isset(self::$_instance[$name])) ? self::$_instance[$name] : new Formo($name);
	}
	
	/**
	 * depends_on method. Mimicks Kohana's built-in helper
	 *
	 * @return bool
	 */					
	public function depends_on($field, array $fields)
	{
		foreach ($fields as $element=>$v)
		{
			if ( ! isset($fields[$element]) OR $fields[$element] == NULL )
				return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * matches method. Mimicks Kohana's built-in helper
	 *
	 * @return bool
	 */					
	public function matches($field_value, array $inputs)
	{
		foreach ($inputs as $element=>$v)
		{
			if ($field_value != $inputs[$element])
				return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * validate method. Runs validate on each element
	 *
	 * @return object
	 */						
	public function validate($append_errors=FALSE)
	{
		Event::run('formo.pre_validate');
		if ($this->_was_validated AND ! $append_errors)
			return $this->_validated;
						
		if ( ! $this->_post_added)
		{
			$this->add_posts();
		}
		
		if ( ! $this->_sent)
			return;
				
		// validate elements
		foreach (($elements = $this->find_elements()) as $key)
		{
			if ( ! $this->_validated AND $this->$key->validate())
			{
				$this->_error = TRUE;
			}
			
			if ($append_errors)
			{
				$this->$key->append_errors();
			}
		}	
		
		if ($this->_was_validated)
			return $this->_validated;
	
		$this->_was_validated = TRUE;		
		$this->_validated = ($this->_error) ? FALSE : TRUE;
				
		Event::run('formo.post_validate');
		
		return ($this->_error) ? FALSE : TRUE;
	}

	/**
	 * _filter_labels method. Runs filters on labels
	 *
	 * NOT USABLE
	 */							
	private function _filter_label($element)
	{
		foreach ($this->_label_filters as $filter)
		{
			$this->$element->filter_label($filter);
		}
	}
	
	// adds a label filter
	public function label_filter($function)
	{
		$this->_label_filters[] = $function;
		
		return $this;
	}

	/**
	 * add_posts method. Called from _prepare, adds post/get
	 * values to elements
	 *
	 * @return bool
	 */							
	public function add_posts()
	{
		if ($this->_post_added)
			return;
				
		if (strtoupper(Input::instance()->post('__formo')) == strtoupper($this->_formo_name))
		{
			$this->_post_type = 'post';
		}
		elseif (strtoupper(Input::instance()->get('__formo')) == strtoupper($this->_formo_name))
		{
			$this->_post_type = 'get';
		}
			
		if ( ! $this->_post_type)
			return;
		
		$type = $this->_post_type;

		Event::run('formo.pre_addpost');
		
		foreach ($this->find_elements() as $element)
		{
			$value = Input::instance()->$type($element);
			$this->$element->add_post($value, $type);
		}

		// first run all "all" filters, then "some" if necessary
		( ! $this->_all_pre_filters() AND $this->_some_pre_filters());

		Event::run('formo.post_addpost');

		$this->_post_added = TRUE;
		$this->_sent = TRUE;
	}	
	 		 			
	private function _all_pre_filters()
	{
		if (empty($this->_pre_filters))
			return FALSE;
			
		if (empty($this->_pre_filters['all']))
			return FALSE;
			
		foreach ($this->find_elements(TRUE) as $element)
		{
			foreach ($this->_pre_filters['all'] as $filter)
			{
				$this->$element->pre_filter($filter);
			}
			
			if (isset($this->_pre_filters[$element]))
			{
				foreach ($this->_pre_filters[$element] as $filter)
				{
					$this->$element->pre_filter($filter);
				}
			}
		}
		
		return TRUE;
	}
	
	private function _some_pre_filters()
	{
		if (empty($this->_pre_filters))
			return;

		foreach ($this->_pre_filters as $element => $filters)
		{
			foreach ($filters as $filter)
			{
				if ( ! isset($this->$element))
					continue;
					
				$this->$element->pre_filter($filter);
			}
		}
	}		
		
	/**
	 * set method. set form object value
	 * 
	 *
	 * @return object
	 */								
	public function set($tag,$value,$other='',$other2='')
	{
		if ($other2 AND $other AND $value AND $tag)
		{
			$this->$tag->$value->$other($other2);
		}
		elseif (isset($this->_elements[$tag]))
		{
			$this->$tag->$value($other);
		}
		else
		{
			$formo_var = (isset($this->$tag)) ? $tag : '_'.$tag;
			$value = (is_array($this->$formo_var)) ? $value : $value;
			$this->$formo_var = $value;
		}
		return $this;
	}

	/**
	 * splitby method. Divide a list into parts
	 *
	 * @return array
	 */													
	public static function splitby($string, $dividers = array(',', '\|'))
	{
		if (is_array($string))
			return $string;
			
		foreach ($dividers as $divider)
		{
			if (preg_match('/'.$divider.'/', $string))
			{
				$array = preg_split('/'.$divider.'/', $string);
				foreach ($array as $k=>$v)
				{
					$array[trim($k)] = trim($v);
				}
				return $array;
			}
		}
		
		return ($string) ? array($string) : array();
	}
	
	public static function has_value($val)
	{
		if ($val)
			return TRUE;
				
		if (strval($val) === '0')
			return TRUE;			
	}

	/**
	 * quicktags method. Simple method for inputting tag sets
	 *
	 * @return array
	 */											
	public static function quicktags($string)
	{	
		if ( ! $string)
			return array();
		if (is_array($string))
			return $string;
			
		$groups = self::splitby($string);
		foreach ($groups as $group)
		{
			$group_parts = explode('=', $group);
			if ( ! isset($group_parts[1]))
				return array('value' => $string);

			$tags[trim($group_parts[0])] = trim($group_parts[1]);
		}
		
		return $tags;
	}
	
	/**
	 * quicktagss method. Formats tags array into formatted html
	 *
	 * @return string
	 */												
	public static function quicktagss($string)
	{
		$tags = self::quicktags($string);
		$str = '';
		$a = 0;
		foreach ($tags as $k=>$v)
		{
			$str.= ' '.$k.'='.'"'.$v.'"';
			$a++;
		}
		return $str;
	}

	/**
	 * into_function method. Takes string like some_function[val, val2][val3, val4]
	 * and returns an array function => some_function, args => 0 => array(val, val2), 1 => array(val3, val4)
	 *
	 * @return array
	 */										
	public static function into_function($string)
	{
		preg_match('/^([^\[]++)/', $string, $matches);
		$function = $matches[0];
		$string = preg_replace('/^'.$function.'/','',$string);
		
		preg_match_all('/\[.+?\]/', $string, $matches);
		$args = array();
		foreach ($matches[0] as $match)
		{
			$match = preg_replace('/[\[\]]/', '', $match);
			$args[] = (preg_match('/,/',$match)) ? preg_split('/(?<!\\\\),\s*/', $match) : $match;
		}
				
		return array($function,$args);
	}
	
	/**
	 * into_array method. Turns $thing into an array if it isn't
	 *
	 * @return array
	 */											
	public function into_array( & $thing)
	{	
		if ( ! is_array($thing))
		{
			$thing = array($thing);
		}

		return $thing;
	}	
	
	/**
	 * _compile_plugins function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _compile_plugins()
	{
		$plugins = array();
		$settings = array
		(
			'values' => Kohana::config('formo.plugins', FALSE, FALSE),
			'type_values' => Kohana::config('formo.'.$this->_formo_type.'.plugins', FALSE, FALSE)
		);
		
		foreach ($settings as $setting)
		{
			if ( ! $setting)
				continue;
			
			$this->plugin($setting);
		}
		
	}
		
	/**
	 * _compile_settings function. From config
	 * 
	 * @access public
	 * @param mixed $setting
	 * @return void
	 */
	public function _compile_settings($setting)
	{
		$formo_var = '_'.$setting;
		
		$settings = array
		(
			'values' => Kohana::config('formo.'.$setting, FALSE, FALSE),
			'type_values' => Kohana::config('formo.'.$this->_formo_type.'.'.$setting, FALSE, FALSE)
		);
		
		if ($setting == 'form_globals')
			return $this->_compile_form_globals($settings);
					
		if ($settings['values'])
		{
			$this->$formo_var = array_merge($this->$formo_var, $settings['values']);
		}
			
		if ($settings['type_values'])
		{
			$this->$formo_var = array_merge($this->$formo_var, $settings['type_values']);
		}
	}
	
	/**
	 * _compile_form_globals function.
	 * 
	 * @access private
	 * @param mixed $settings
	 * @return void
	 */
	private function _compile_form_globals($settings)
	{
		foreach ($settings as $values)
		{
			if (empty($values))
				continue;
			
			foreach ($values as $property => $value)
			{
				$this->{'_'.$property} = $value;
			}
		}
	}
			
	/**
	 * pre_filter method. Add a pre_filter
	 *
	 * @return object
	 */												
	public function pre_filter($element, $function='')
	{
		$this->_pre_filters[$element][] = $function;
		
		return $this;
	}
	
	/**
	 * pre_filters method. Add a pre_filter to a set of elements
	 *
	 * @return object
	 */
	public function pre_filters($function, $elements)
	{
		$_functions = self::splitby($function);
		$_elements = self::splitby($elements);
		
		foreach ($_functions as $_function)
		{
			foreach ($_elements as $_element)
			{
				$_element = trim($_element);
				$this->pre_filter(trim($_element), $_function);
			}		
		}		
		
		return $this;
	}
	
	public function rule($rule, $message='')
	{
		$this->{self::$last_accessed}->add_rule($rule, $message);
		
		return $this;
	}
	
	public function add_rule($element, $rule, $message='')
	{
		if ( ! isset($this->$element))
		{
			$this->_auto_rules[$element] = array($rule, $message);
			return $this;
		}
			
		$this->$element->add_rule($rule, $message);
		
		return $this;
	}
	
	/**
	 * add_rules. Add a rule to a set of elements
	 *
	 * @return object
	 */
	public function add_rules($rule, $elements, $message='')
	{
		$_elements = self::splitby($elements);
		if (is_array($rule))
		{
			foreach ($rule as $_rule)
			{
				return $this->add_rules($_rule, $elements, $message);
			}	
		}
		
		foreach ($_elements as $_element)
		{
			if ( ! isset($this->$_element))
				continue;
			
			$_element = trim($_element);
			$this->$_element->add_rule($rule, $message);
		}
		
		return $this;
	}
	
	public function clear()
	{
		foreach ($this->find_elements(TRUE) as $element)
		{			
			$this->$element->clear();
		}

		$this->form->post_added = FALSE;
		$this->form->was_validate = FALSE;
		$this->form->validated = FALSE;
		$this->form->sent = FALSE;
		$this->cleared = TRUE;
		
		Session::instance()->set_flash('__formo_'.$this->_formo_name.'_cleared', TRUE);
				
		return $this;
	}	
		
	/**
	 * _make_defaults method. Append default tags to element
	 *
	 * @return array
	 */
	private function _make_defaults($type, $name)
	{
		$defaults = array();		
		if (isset($this->_defaults[$type]))
		{
			$defaults = array_merge($this->_defaults[$type], $defaults);
		}		
	
		if (isset($this->_defaults[strtolower($name)]))
		{
			$defaults = array_merge($defaults, $this->_defaults[strtolower($name)]);
		}
				
		return $defaults;
	}
	
	public static function include_file($type, $file)
	{
		if (in_array($file, self::$__includes))
			return;
			
		$path = 'libraries/formo_'.$type.'s';

		$_file = Kohana::find_file($path, $file);
		if ( ! $_file)
		{
			$_file = Kohana::find_file($path.'_core', $file);
		}
				
		include_once($_file);
		self::$__includes[] = $file;
	}				

	/**
	 * add method. Add a new Form_Element object to form
	 *
	 * @return object
	 */
	public function add($type,$name='',$info=array())
	{
		$original_type = $type;
		if ( ! $info AND ! $name)
		{
			$name = $type;
			$type = 'text';
		}
		elseif ( ! $info AND (is_array($name) OR preg_match('/=/',$name)))
		{
			$info = $name;
			$name = $type;
			$type = 'text';
		}
		
		$obj_name = strtolower(str_replace(' ', '_', $name));
		
		if (isset($this->$obj_name))
			return $this;

		// check if this needs to change types first
		if ($original_type != $type AND isset($this->_defaults[strtolower($name)]['type']))
		{
			$type = $this->_defaults[strtolower($name)]['type'];
		}
		
		$info = $this->process_info($info, $type, $name);
		
		$info['type'] = $type;
				
		self::include_file('driver', $info['type']);
		
		$file = 'Formo_'.$info['type'].'_Driver';
		
		$el = new $file($name, $info);

		$this->$obj_name = $el;
		self::$last_accessed = $obj_name;
				
		$this->_attach_auto_rule($obj_name);


		return $this;
	}
	
	public function process_info($info, $type, $name)
	{
		$defaults = $this->_make_defaults($type, $name);
		$defaults_globals = array_merge($this->_globals, $defaults);

		$info = self::quicktags($info);
		$info = array_merge($defaults_globals, $info);
		$info['formo_name'] = $this->_formo_name;
		$info['type'] = ( ! empty($info['type'])) ? $info['type'] : $type;
		
		return $info;
	}
	
	/**
	 * add_group method. Add a new Form_Group object to form
	 *
	 * @return object
	 */
	public function add_group($name, $values, $info=NULL)
	{
		$add_name = strtolower(preg_replace('/\[\]/','',$name));

		$defaults = $this->_make_defaults($add_name, $add_name);
		$defaults_globals = array_merge($this->_globals, $defaults);
		
		$this->$add_name = Formo_Group::factory($name, $values, $info);

		foreach ($defaults_globals as $setting => $value)
		{
			$this->$add_name->$setting = $value;
		}

		self::$last_accessed = $add_name;
						
		return $this;
	}
			
	/**
	 * _attach_auto_rule method. Attach an auto rule to appropriate element
	 *
	 */
	private function _attach_auto_rule($name)
	{
		if ( ! isset($this->_auto_rules[$name]))
			return;
		
		if (is_array($this->_auto_rules[$name][0]))
		{
			foreach ($this->_auto_rules[$name] as $rule)
			{
				$this->add_rule($name, $rule[0], $rule[1]);
			}
		}
		elseif (is_array($this->_auto_rules[$name]))
		{
			$this->add_rule($name, $this->_auto_rules[$name][0], $this->_auto_rules[$name][1]);
		}
		else
		{
			$this->add_rule($name, $this->_auto_rules[$name]);
		}		
	}
			
	/**
	 * remove method. Removes an element from form object
	 *
	 * @return object
	 */
	public function remove($element)
	{
		if (is_array($element))
		{
			foreach ($element as $el)
			{
				$this->remove($el);
			}
		}
		else
		{
			if ( ! isset($this->$element))
				return $this;
				
			unset($this->$element);
			unset($this->_elements[$element]);
		}
		
		return $this;
	}

	/**
	 * find_elements method. return all elements. Use order if applicable
	 *
	 * @return array
	 */	
	public function find_elements($skip_first = FALSE)
	{
		if ($this->_order)
		{
			$elements[] = '__formo';
			foreach ($this->_order as $v)
			{
				$elements[] = $v;
			}
			return $elements;
		}
		else
		{
			$arr = array_keys($this->_elements);
			($skip_first === TRUE and array_shift($arr));
			
			return $arr;
		}
	}

	/**
	 * _elements_to_string method. Build a string of formatted text with all the
	 * form's elements
	 *
	 * @return string
	 */		
	protected function _elements_to_string($return_as_array=FALSE)
	{
		$str = '';
		foreach (($elements = $this->find_elements()) as $key)
		{
			if ($return_as_array)
			{			
				$elements_array[$key] = $this->$key;
				$elements_array[$key.'_error'] = $this->$key->error;
			}
			else
			{
				$this->_filter_label($key);
				$str .= $this->$key->get();			
			}
	
		}
		if ($return_as_array)
			return $elements_array;
			
		return $str;
	}
	
	/**
	 * _make_opentag method. Format form open tag
	 *
	 * @return string
	 */	
	private function _make_opentag()
	{
		$search = array('/{action}/','/{method}/','/{class}/','/{name}/');
		$replace = array($this->_action,$this->_method,$this->_class,$this->_formo_name);
		return preg_replace($search,$replace,$this->_open);
	}
					
	/**
	 * get method. Return formatted array or entire form
	 * this is equivelant to toString
	 *
	 * @return string or array
	 */			
	public function get($as_array=FALSE)
	{
		$this->add_posts();

		Event::run('formo.pre_render');

		if ($this->_sent)
		{
			$this->validate(TRUE);
		}
		
		$this->_return = ($as_array == TRUE) ? $this->_get_array() : $this->_get();
		
		Event::run('formo.post_render');

		return $this->_return;
	}

	/**
	 * render method. Alias for get()
	 *
	 * @return string or array
	 */				
	public function render($as_array=FALSE)
	{
		$this->get($as_array);
	}	

	/**
	 * _get_array method. Used with get, processes into an array
	 *
	 * @return array
	 */			
	private function _get_array()
	{
		$form = $this->_elements_to_string(TRUE);
		$form['open'] = $this->_make_opentag()."\n".$this->__formo->get();
		$form['close'] = $this->_close;
		
		return $form;
	}

	/**
	 * _get method. Used with get, processes into an string
	 *
	 * @return string
	 */				
	private function _get()
	{	
		$form = $this->_make_opentag()."\n";
		$form.= $this->_elements_to_string();
		$form.= $this->_close."\n";
		
		return $form;
	}
	
	// returns array of all errors
	public function get_errors()
	{
		$this->validate();

		$errors = array();
		foreach ($this->find_elements(TRUE) as $element)
		{
			if ($this->$element->error)
			{
				$errors[$element] = $this->$element->error;
			}			
		}
		
		return $errors;
	}
	
	public function get_values()
	{
		$this->validate();
		
		$values = array();
		foreach (($elements = $this->find_elements(TRUE)) as $element)
		{
			if (($val = $this->$element->get_value()) === FALSE)
				continue;
				
			$values[$element] = $val;
		}
		return $values;
	}	

}	// End Formo