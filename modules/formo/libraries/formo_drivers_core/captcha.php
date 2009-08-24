<?php defined('SYSPATH') or die('No direct script access.');

class Formo_captcha_Driver extends Formo_Element {

	protected $captcha;
	protected $config;
	protected $non_images = array('math', 'riddle');

	// special defaults for Captcha
	public $error_msg = 'Does not match';
	public $element_open = '<span class="captcha">';
	public $element_close = '</span>';
	
	// specific captcha variables
	public $captcha_open = '<span class="captcha_challenge">';
	public $captcha_close = '</span>';
	public $input_open;
	public $input_close;
	public $clear = '<div class="clear"></div>';
	public $group;

	
	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
		
		// load the config file
		$_config = Kohana::config('captcha');
		
		// get default if no group specified, group if it is
		$config = ($this->group AND isset($_config[$this->group]))
		        ? $_config[$this->group]
		        : $_config['default'];
		
		// create the captcha object here if it's a riddle
		if (in_array($config['style'], $this->non_images))
		{
			$this->captcha = Captcha::factory($this->group);
		}
	}
	
	public function render()
	{
		// create captcha object here if it's an image
		if ( ! $this->captcha)
		{
			$this->captcha = Captcha::factory($this->group);
		}		
		
		// add the clear html to the close tag
		$this->close = $this->clear.$this->close;
		
		// only load the captcha if we're not sure it's a human
		if ( ! $this->captcha->promoted())
		{
			return $this->captcha_open."\n"
				 . $this->captcha->render(TRUE)."\n"
				 . $this->captcha_close
				 . $this->input_open
				 . '<input type="text" name="'.$this->name.'"'
				 . Formo::quicktagss($this->_find_tags()).' />'."\n"
				 . $this->input_close;
		}		
	}

	protected function validate_this()
	{
		// use Captcha's buit-in validation
		if ( ! Captcha::valid(Input::instance()->post($this->name)))
		{
			$this->error = $this->error_msg;
			
			return $this->error;
		}
	}	
}