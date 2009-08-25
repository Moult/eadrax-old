<?php defined('SYSPATH') or die('No direct script access.');

/*
 * Autocomplete Formo driver
 * 
 * Uses:
 * 
 * jQuery 1.3.2. (http://www.jquery.com)
 * Autocomplete Plugin (http://www.devbridge.com/projects/autocomplete/jquery/#download)
 *  
 * Three modes are available (default mode is 'simple'):
 * 
 * 1. 'simple': Simple autocomplete with just a list of selectable values.
 * In this case you can pass a comma/pipe separated list when creating
 * the autocomplete, eg.
 *  	
 * 		$form->add_autocomplete('name','mode=simple,data=First|Second|Third')
 * 
 * Or you can use a method call like in the next modes.
 *  
 * 2. 'paired': A value->data linked autocomplete, where chosen value's data is 
 * inserted in a hidden input field. Class::method is responsible 
 * for returning an array of matched items, query string is passed
 * as an argument to method. Return must be an array, where keys are
 * the items seen in the list and values are the data, eg.
 *  
 * 		$form->add('autocomplete','name','mode=paired,source=Class::method')
 * 
 * 3. 'multi': Same as paired mode, but user can select multiple items.
 * 
 * 		$form->add('autocomplete','name','mode=multi,source=Class::method')
 * 
 * Example data fetching method (User_Model::listing):
 * 
 * public function listing($query)
 * {
 *     $results = ORM::factory('user')
 *	   ->like('firstname',$query.'%',false)
 *	   ->limit(10)
 *	   ->find_all();
 *			
 *	   foreach($results as $row)
 *	   {
 *	      $list[$row->name] = $row->id;
 *	   }
 *		
 *	   return $list;
 * }
 *  
 */
 
class Formo_autocomplete_Driver extends Formo_Element {
	
	protected $source;
	protected $data;
	public $mode = 'simple';
	
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
						
		if(request::is_ajax() AND isset($_GET['query']))
		{
			if(!$this->data)
			{
				$this->data = call_user_func($this->source,$_GET['query']);
				$this->get_results();
			}
		}		
	}	
		
	public function add_info($info)
	{
		$info = Formo::quicktags($info);
		foreach ($info as $k=>$v)
		{
			if($k == 'source')
			{
				$this->source = explode('::', $v);
			}
			elseif($k == 'data')
			{
				$this->data = Formo::splitby($v);
			}
			elseif($k == 'mode')
			{
				$this->mode = $v;
			}
			else
			{
				$this->$k = $v;
			}
		}
	}
	
	public function get_results()
	{
		die(json_encode(array(
				'query' => $_GET['query'],
				'suggestions' => array_keys($this->data),
				'data' => array_values($this->data)
			)
		));
	}
	
	public function render()
	{
		$quicktagss = Formo::quicktagss($this->_find_tags());
		$current_url = url::site(url::current());
		
		switch ($this->mode)
		{
			case 'simple':
				// if a source was defined, get the data from there
				// else use the predefined data				
				$source = $this->source
					? "serviceUrl: '".$current_url."'" 
					: "lookup: ['".implode("', '",$this->data)."']";
				
				return <<<EOT
	<input type="text" rel="simple" name="$this->name" id="$this->name" value="$this->value"$quicktagss />
	<script type="text/javascript">
	$(document).ready(function(){
		$('input#$this->name','form[name=$this->formo_name]').autocomplete({
			$source
		});
	});
	</script>
EOT;
				break;
				
			case 'paired':			

				$classes = $this->class.' autocomplete';	

				return <<<EOT
	<input type="text" rel="paired" id="$this->name" class="$classes" />
	<input type="hidden" name="$this->name" value="$this->value"$quicktagss />
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('input#$this->name','form[name=$this->formo_name]').autocomplete({
			serviceUrl:'$current_url',
			onSelect: function(value, data){
				$('input[id=$this->name]')
					.nextAll('[name=$this->name]')
					.val(data)
					.trigger('change');
			}
		}).blur(function(){
			if($(this).val() == '')
			{
				$(this).nextAll('[name=$this->name]')
					.val('')
					.trigger('change');
			}
		});
	});
	</script>
EOT;
				break;
				
			case 'multi':		

				$this->remove_class('ajaxval');
				$classes = $this->class.' autocomplete';	

				return <<<EOT
	<input type="text" rel="multi" id="$this->name" class="$classes" />
	<script type="text/javascript">
	$(document).ready(function(){		
		var ac_input = $('input#$this->name','form[name=$this->formo_name]');
		
		ac_input.autocomplete({
			serviceUrl:'$current_url',
			onSelect: function(value, data){
				
				// create an element that represents the data/value pair
				var ac_item = $('<a href="#" class="autocomplete_item" id="'+data+'">' + value
				+ '<input type="hidden" name="$this->name[]" value="'+data+'" /></a>');
				
				// insert it before the autocomplete input
				ac_item.insertBefore(ac_input);
				
				ac_input.val('').focus();
			}
		});
		
		$('a.autocomplete_item').live('click',function(){
			$(this).remove();
		});
	});
	</script>
EOT;
				break;
		}
	}
	
}