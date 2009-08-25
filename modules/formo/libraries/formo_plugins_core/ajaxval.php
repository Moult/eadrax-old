<?php defined('SYSPATH') or die('No direct script access.');

/*======================================

Ajaxval Formo Plugin by Jussi Tulisalo

Automated Ajax validation (element-based)

Uses:

 - jQuery (tested on jQuery v. 1.3.2)	http://www.jquery.com
 - jQuery Form Plugin	http://malsup.com/jquery/form/#download
  
 * scripts aren't included, so you have to add them yourself

Methods added:

$form->no_ajax():

You can remove form's (or elements') ajax functionality.
Passing no argument removes it completely for that form.
You can pass an array/string of elements too.
(commas and pipes allowed, eg. $form->no_ajax('title|username|something') )

NOTICE!

You have to disable auto-rendering of views (if in use) for ajax calls.
In main controller's construct:

		if(request::is_ajax())
		{
			$this->auto_render = FALSE;
		}

======================================*/

class Formo_ajaxval {
	
	public $form;
	protected $ajaxed_forms;
	static protected $script_already_added;
	public $valid_message = 'OK';
	public $message_class = 'ajax_validation';
	public $valid_class = 'success';
	public $error_class = 'error';
	public $input_class = 'errorInput';
	public $loading_gif_url = '/modules/formo/assets/img/ajax-loader.gif';
		
	public function __construct( & $form)
	{
		$this->form = $form;
		
		// add form to ajaxed forms list
		$this->ajaxed_forms[] = $this->form->_formo_name;
		
		// do the jQuery script rendering only once
		if (self::$script_already_added == false) {		
			Event::add('system.display', array($this,'add_jquery'));
            self::$script_already_added = true;
        }
		
		// add possibility to remove ajax functionality
		$this->form->add_function('no_ajax', array($this,'no_ajax'));
		
		// add 'ajaxval' and possible 'required' class to elements 
		Event::add('formoel.pre_render', array($this, 'mark_ajaxed_and_required'));
		
		if(request::is_ajax() AND isset($_GET['element']) AND in_array($this->form->_formo_name, $this->ajaxed_forms))
		{
				Event::add('formogroup.pre_validate', array($this, 'ajax_group_validation'));		
				Event::add('formoel.post_validate', array($this, 'ajax_validation'));	
		}
	}
	
	public static function load( & $form)
	{
		return new Formo_ajaxval($form);
	}
	
	/**
	 * Removes Ajax functionality (marks form/element(s) with class 'no_ajax')
	 *
	 * @param   empty,string,array   elements to mark
	 */
	public function no_ajax($elements = NULL)
	{
		if(!$elements)
		{		
			foreach($this->form->find_elements() as $element)
			{
				// remove the 'ajaxval' class from the element
				$this->form->{$element}->class = str_replace(' ajaxval', '', $this->form->{$element}->class);
			}
			
			return $this->form;
		}
		
		$elements = (func_num_args() > 1) ? func_get_args() : $this->form->splitby($elements);
				
		foreach($elements as $element)
		{
			$this->form->{$element}->class = str_replace(' ajaxval', '', $this->form->{$element}->class);
		}
		
		return $this->form;
	}
	
	// mark required elements with a class 'required' and all ajaxed elements with 'ajaxval'
	public function mark_ajaxed_and_required()
	{
		$element = Event::$data;		
		
		// ignore certain elements
		$ignored_elements = array('submit','button','hidden');
		if(!in_array($element->type, $ignored_elements))
		{
			if(!strpos($element->class, 'ajaxval'))
			{
				$element->class .= ' ajaxval';
			}
			
			if($element->required AND !strpos($element->class, 'required'))
			{
				$element->class .= ' required';
			}
		}
	}
	
	// appends jQuery script to <body> tag
	public function add_jquery()
	{
		$action = $this->form->_action ? $this->form->_action : url::site(url::current());
		
		// only affect forms that have used ajaxval
		$ajaxed_forms = 'form[name='.implode('][name=', $this->ajaxed_forms).']';
		
		$script = <<<EOT
<style type="text/css">
form .ajax_loading {background: #fff url($this->loading_gif_url) 97% 50% no-repeat;}
</style>

<script type="text/javascript">
$(document).ready(function(){	

	var elements = $('.ajaxval',$('$ajaxed_forms'));

	// add a span element to all inputs for the ajax messages	
	$($('<span class="$this->message_class"></span>')).appendTo(elements.parents('p'));
		
	// on change event, validate element using current url & element's name
	$(elements).change(function(){

		var element = $(this);
		
		// add a loading class to the element, and remove it after ajax call
		element.addClass('ajax_loading');
		
		if(element.hasClass('autocomplete'))
		{
			autocomplete = element;
			element = element.nextAll('input[name='+element.attr('id')+']');
		}
		
		var message = $('span.$this->message_class',element.parents('p'));

		element.parents('form').ajaxSubmit({
			url: '$action?element='+element.attr('name'),
			target: message,
			success: function(data){
				
				data = jQuery.trim(data);
								
				// if an error message is present, remove it
				$('span[class*=error]',element.parents('p')).not(message).remove();
																			
				if(data == '$this->valid_message') // if valid
				{
					element.removeClass('$this->input_class');
					message.removeClass().addClass('$this->message_class $this->valid_class');
				}
				else // if error
				{
					element.addClass('$this->input_class');
					message.removeClass().addClass('$this->message_class $this->error_class');
				}		
				element.removeClass('ajax_loading');
				autocomplete.removeClass('ajax_loading');
			}
		});
	});
	
	// on blur event, validate all required elements that are empty
	$('.required',$('$ajaxed_forms')).blur(function(){
		var element = $(this).hasClass('autocomplete')
			? $(this).nextAll('input[name='+$(this).attr('id')+']')
			: $(this);	
			
		if(element.val() == '')
		{
			element.change();
		}
	});
});
</script>
EOT;
				
		Event::$data = str_replace("</body>", $script . " </body>", Event::$data);
	}	
	
	// element validation, returns error message or null if valid
	public function ajax_validation()
	{
		$element = Event::$data;
		// if element is what we are looking for, ignore group items
		if($element->name == $_GET['element'] OR $element->name == 'autocomplete' AND $element->type)
		{
			// return error / success
			die($element->error ? $element->error : $this->valid_message);
		}
	}		
	
	// group validation, returns error message or null if valid
	public function ajax_group_validation()
	{		
		$group = Event::$data;
		// if element is what we are looking for
		if($group->name == str_replace('[]', '', $_GET['element']))
		{
			// validate			
			if ($group->required AND ! Input::instance()->post($group->name))
			{
				$group->error = $group->required_msg;
			}
			elseif ($group->rule)
			{
				$rules = ( ! is_array($group->rule)) ? array($group->rule) : $group->rule;
				
				foreach ($rules as $rule)
				{
					$function = (isset($rule['rule'])) ? $rule['rule'] : $rule;
					$error_msg = (isset($rule['error_msg'])) ? $rule['error_msg'] : $group->error_msg;
					$type = $group->post_type;
	
					if ( ! call_user_func($function, Input::instance()->$type($group->name)))
					{
						$group->error = $error_msg;
					}
				}
			}
			
			die($group->error ? $group->error : $this->valid_message);
		}
	}		
}