<?php defined('SYSPATH') or die('No direct script access.');

/*
	Version 1.1.6
	avanthill.com/formo_manual/
	
	Requires Formo and Formo_Group
	Kohana 2.2
*/

class Formo_Element_Core {
	
	public $formo_name;
	public $order = array('label','element','error');

	public $open = '<p>';
	public $close = '</p>';
	
	public $label;
	public $label_open = '<label for="{name}">';
	public $label_close = ':</label>';
	
	public $element_open;
	public $element_close;
	
	public $attributes = array();
	public $tags = array();
	
	public $name;
	public $value;
	public $type;
	
	public $was_validated = FALSE;
	
	public $required = TRUE;
	public $rule = array();
	
	public $error_msg = "Invalid";
	public $required_msg = "Required";
	public $show_error;
	public $error;
	public $error_open = '<span class="{error_msg_class}">';
	public $error_close = '</span>';
	public $error_class = 'errorInput';
	public $error_msg_class = 'errorMessage';
	

	/**
	 * Magic __toString method. Returns all element without label
	 * or open and close.
	 *
	 * @return  string
	 */	
	function __toString() {
		return $this->render();
	}

	/**
	 * Magic __construct method. creates element object,
	 * $name, and $info
	 *
	 */	
	public function __construct ($name='',$info=array())
	{
		$data = array('object'=>$this, 'name'=>$name, 'info'=>$info);
		Event::run('formoel.preload', $data);
				
		$this->name = strtolower(str_replace(' ', '_', $data['name']));
		$this->id = $this->name;
		$this->label = $data['name'];
		if ($data['info'])
		{
			$this->add_info($data['info']);
		}		

		Event::run('formoel.add', $this);
	}
	
	public function add_info($info)
	{
		$info = Formo::quicktags($info);
		foreach ($info as $k=>$v)
		{
			$this->$k = $v;
		}
	}

	/**
	 * Magic __set method. Keeps track of tags added to element
	 *
	 * @return  string
	 */	
	public function __set($var, $val)
	{
		if ( ! is_object($val) AND ! in_array($var, $this->attributes))
		{
			$this->tags[] = $var;
			$this->$var = $val;
		}
		elseif ( ! is_object($val))
		{
			$this->$var = $val;
		}
		elseif (get_class($val) == 'Formo_Element')
		{
			$this->elements[$var] = $val->type;
		}
	}

	// returns null to avoid errors when dealing with
	// template-makers
	public function __get($variable)
	{
		return;
	}
	
	// makes attribute equal to value[0]
	public function __call($function ,$values)
	{
		$this->$function = $values[0];
		
		return $this;
	}
	
	
	// the default shortcut method
	public static function shortcut($defs, $name, $info=array())
	{
		$class = 'Formo_'.$defs['type'].'_Driver';
		$info = self::process_info($defs, $info);
		
		return new $class($name, $info);
	}
	
	// does the ugly processing so we can do clean processing in shortcut
	public static function process_info($defs, $info, $name=NULL)
	{
		$name = ($name) ? $name : $defs['name'];
		return Formo::instance($defs['formo_name'])->process_info($info, $defs['type'], $name);
	}

	 // adds an element attribute
	public function add_attribute($att)
	{
		$this->attributes[] = $att;
	}

	// Adds a class to the object
	public function add_class($class)
	{
		if (is_array($class))
		{
			foreach ($class as $v)
			{
				$this->add_class($v);
			}
		}
		else
		{
			$this->class = (isset($this->class)) ? $this->class.' '.$class : $class;
		}
		
		return $this;
	}

	// remove_class method. Removes class from object
	// Deletes class tag if none exist after function
	public function remove_class($class)
	{
		if (is_array($class) AND isset($this->class))
		{
			foreach ($class as $v)
			{
				$this->remove_class($v);
			}
		}
		elseif (isset($this->class))
		{
			$this->class = preg_replace("/ *$class/",'',$this->class);
			if (!$this->class OR $this->class == ' ') {
				$this->remove_tag('class',TRUE);
			}
		}
		
		return $this;
	}
	
	// runs callback on label
	public function filter_label($filter)
	{
		$this->label(call_user_func($filter, $this->label));
	}
	
	// clears element
	public function clear()
	{
		$this->value(NULL);
	}

	// add_rule method. Adds a rule to the object		
	public function add_rule($rule, $error_msg='')
	{
		if ($rule == 'required')
		{
			$this->required = TRUE;
			$this->required_msg = ($error_msg) ? $error_msg : $this->required_msg;
			return;
		}
		elseif ($rule == 'not_required')
		{
			$this->required = FALSE;
			return;
		}
		Formo::into_array($this->rule);

		if (is_array($rule) AND is_object($rule[0]))
		{
			$this->rule[] = array
			(
				'rule'		=> $rule,
				'error_msg'	=> ($error_msg) ? $error_msg : $this->error_msg
			);
		}
		elseif (is_array($rule))
		{
			$this->rule = array_merge($this->rule, $rule);
		}
		else
		{
			$error_msg = ($error_msg) ? $error_msg : $this->error_msg;
			$this->rule[] = array('rule'=>$rule, 'error_msg'=>$error_msg);
		}		
		
		return $this;
	}

	// remove_tag method. Removes a tag from object
	public function remove_tag($tag)
	{
		if (isset($this->$tag))
		{
			unset($this->$tag);
		}
		if ($key = array_keys($this->tags, $tag))
		{
			unset ($this->$tag[$key[0]]);
		}
		
		return $this;
	}

	// _find_tags method. returns an array of all
	protected function _find_tags()
	{
		foreach ($this->tags as $tag)
		{
			$tags[$tag] = $this->$tag;
		}
		return $tags;
	}

	// do_rule method. runs object rules
	// returns  true if valid, false if invalid
	protected function _do_rule($rule)
	{
		$rule = (is_array($rule) AND isset($rule['rule'])) ? $rule['rule'] : $rule;
		$form_level_rules = array('match','depends_on');
						
		if (isset($rule[0]) AND is_object($rule[0]))
			return call_user_func($rule, $this->value);
			
		list($function,$args) = Formo::into_function($rule);

		if ($function == 'matches' OR $function == 'depends_on')
		{
			$values = array();
			foreach ($args as $match)
			{
				if (is_array($match))
				{
					foreach ($match as $match_val)
					{
						$values[$match_val] = Input::instance()->post($match_val);
					}
				}
				else
				{
					$values[$match] = Input::instance()->post($match);
				}				
			}

			return Formo::$function($this->value, $values);
		}

		if (preg_match('/::/', $function))
		{
			array_unshift($args, $this->value);
			return call_user_func_array($function, $args);
		}
		elseif (method_exists('valid', $function))
		{
			array_unshift($args, $this->value);
			return call_user_func_array('valid::'.$function, $args);
		}
		elseif (method_exists('Validation', $function))
		{
			$use_args = ( ! isset($args[0])) ? array() : $args[0];
			$use_args = ( ! is_array($args[0])) ? array($use_args) : $use_args;
			return Validation::$function($this->value, $use_args);
		}
		elseif ( ! in_array($function, $form_level_rules))
		{
			array_unshift($args, $this->value);
			return call_user_func_array($function,$args);
		}
		
	}
		
	// driver-specific function for how validation is handled
	protected function validate_this()
	{
		$this->strip_error();
		$done_already = FALSE;
		
		if ($this->value AND $this->rule)
		{
			foreach (Formo::into_array($this->rule) as $rule)
			{
				if ( ! $this->_do_rule($rule))
				{
					$this->error = $this->error_msg;
					$this->error = (is_array($rule) AND isset($rule['error_msg'])) ? $rule['error_msg'] : $this->error_msg;
					break;
				}
			}
		}
		elseif ($this->required AND strtoupper($this->required !== 'FALSE') AND strtoupper($this->required) !== 'F' AND ! Formo::has_value($this->value))
		{
			$this->error = $this->required_msg;
		}
	}
	
	// validate method. checks to see if element is required	
	public function validate()
	{
		if ($this->error)
			return $this->error;
		
		Event::run('formoel.pre_validate', $this);
		$this->validate_this();
		Event::run('formoel.post_validate', $this);

		$this->was_validated = TRUE;
		return $this->error;
	}

	// add post data to element	
	public function add_post($value)
	{
		$this->value = htmlspecialchars_decode($value);
	}
		
	// make errors actually show
	public function append_errors()
	{
		if ($this->error)
		{
			$this->error = $this->error;
			$this->add_class($this->error_class);
		}
	}

	// strip method. strips error from element object
	public function strip_error()
	{
		$this->error = '';
		$this->remove_class($this->error_class);
	}

	// error method. Processes error into formatted text
	public function error($msg='')
	{
		if ($msg)
		{
			$this->error = $msg;
			$this->append_errors();
		}
		elseif ($this->error)
		{
			$open = preg_replace('/{error_msg_class}/',$this->error_msg_class,$this->error_open);
			return $open.$this->error.$this->error_close;
		}
	}
	
	// change element's type
	public function type($new_type)
	{
		Formo::include_file('driver', $new_type);
		$form = Formo::instance($this->formo_name);
		
		$vals = get_object_vars($this);

		unset($form->{$this->name});
		unset($vals['type']);
		unset($vals['tags']);
		
		$form->add($new_type, $this->name, $vals);
		
		return $this;
	}

	// element method. returns element with formatting
	public function element()
	{
		return $this->element_open	.
			   $this->render()		.
			   $this->element_close;
	}
	
	// runs filter on value
	public function pre_filter($filter)
	{
		$this->value(call_user_func($filter, $this->value));
	}

	// label method. returns formatted label
	public function label($value=NULL)
	{
		if ($value)
		{
			$this->label = $value;
			return $this;
		}
		
		if ( ! $this->label)
			return;
		$open = str_replace('{name}', $this->name, $this->label_open);

		return $open
			  .$this->label
			  .$this->label_close;
	}
	
	public function get_value()
	{
		if ($this->name == '__form_object')
			return FALSE;
		
		return $this->value;
	}
	
	protected function build()
	{
		$str = '';
		foreach ($this->order as $part)
		{

			$str .= $this->$part();
		}

		return "\t".$this->open
			  .$str
			  .$this->close."\n";
	}
	
	protected function build_array()
	{
		return $this->element();
	}

	/**
	 * get method. Fully turns element into formatted text
	 *
	 * @return  text or array
	 */									
	public function get($get_as_array = FALSE)
	{
		Event::run('formoel.pre_render', $this);
		if ( ! $get_as_array)
		{
			return $this->build();
		}	
		else
		{
			return $this->build_array();
		}
		Event::run('formoel.post_render', $this);
	}
			
}
// End Form_Element