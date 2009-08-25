<?php defined('SYSPATH') or die('No direct script access.');

/*======================================

Auto_i18n Formo Plugin by Jussi Tulisalo

Tries to fetch localized strings for errors,
button values and labels, and falls back to
originals if not found

Usage example:

$form = Formo::factory()
	->plugins('auto_i18n')
	->add('test')
	->add_rule('test','length[2,10]','invalid_length')
	->add_button('try_it');

When rendered, by default Formo now looks for:

Kohana::lang('formo.labels.test')
Kohana::lang('formo.errors.invalid_length')
Kohana::lang('formo.buttons.try_it')

======================================*/

class Formo_auto_i18n {

	// default i18n locations, notice the trailing dot
	public $i18n_labels = 'formo.labels.';
	public $i18n_errors = 'formo.errors.';
	public $i18n_buttons = 'formo.buttons.';
	
	public $form;
	
	public function __construct( & $form)
	{		
		Event::add('formoel.pre_render', array($this, 'i18n_stuff'));
		$this->form = $form;
	}
	
	public static function load( & $form)
	{
		return new Formo_auto_i18n($form);
	}
	
	// Fetches i18n string for buttons, labels and error
	// from given i18n file using element's name / error msg as the key
	public function i18n_stuff()
	{
		$element = Event::$data;
		
		// skip the __form_object and already internationalized elements
		if($element->name != '__form_object' AND !in_array('i18nd',$element->attributes))
		{
			// fetch label's i18n string, if not found use element's name
			if($element->label AND !in_array($element->type,array('button','submit')))
			{
				if(Kohana::lang($this->i18n_labels.$element->name) == $this->i18n_labels.$element->name)
				{
					$element->label = ucfirst($element->name);
				}				
				else $element->label = Kohana::lang($this->i18n_labels.$element->name);
			}
			
			// fetch button's i18n string, if not found use element's original value
			if(in_array($element->type,array('button','submit')))
			{
				if(Kohana::lang($this->i18n_buttons.$element->name) == $this->i18n_buttons.$element->name)
				{
					$element->value = ucfirst($element->value);
				}
				else $element->value = Kohana::lang($this->i18n_buttons.$element->name);
			}
			
			// fetch error's i18n string, if not found use element's original error
			if($element->error AND $element->error_msg_class != 'comment')
			{
				if(Kohana::lang($this->i18n_errors.$element->error) == $this->i18n_errors.$element->error)
				{
					$element->error = ucfirst($element->error);
				}
				else $element->error = Kohana::lang($this->i18n_errors.$element->error);
			}
			
			// mark the element as internationalized
			$element->add_attribute('i18nd');
		}
	}
		
}