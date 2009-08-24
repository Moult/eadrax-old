<?php defined('SYSPATH') or die('No direct script access.');

/*===============================

This plugin changes all the default element parameters
to be table-based instead of paragraph-based.

===============================*/

class Formo_table {

	protected $form;

	public function __construct( & $form)
	{
		Event::add('formo.pre_construct', array($this, 'set_form_tags'));
		Event::add('formo.pre_construct', array($this, 'set_element_tags'));

		$this->form = $form;
		
		$this->set_element_tags();
		$this->set_form_tags();
	}
	
	public static function load( & $form)
	{
		return new Formo_table($form);
	}
	
	public function set_form_tags()
	{
		$this->form->_open = '<form action="{action}" method="{method}" class="{class}"><table>';
		$this->form->_close = '</table></form>';
	}
	
	public function set_element_tags()
	{
		$this->form->_globals['open'] = '<tr>';
		$this->form->_globals['close'] = '</tr>';
		$this->form->_globals['label_open'] = '<th>';
		$this->form->_globals['label_close'] = ':</th>';
		$this->form->_globals['element_open'] = '<td>';
		$this->form->_globals['element_close'] = '</td>';
		$this->form->_globals['error_open'] = '<td><span class="{error_msg_class}">';
		$this->form->_globals['error_close'] = '</span></td>';

		$this->form->_defaults['submit']['open'] = '<tr><td></td>';
		$this->form->_defaults['submit']['class'] = 'table_submit';
	}

} // end Formo_table plugin