<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 *  http://code.google.com/p/kohana-mptt/source/browse/trunk/test/Head.php
 *  r56
 */

class Head_Core extends ArrayObject {

	// Head singleton
	private static $instance;

	/**
	 * Head instance of Head.
	 */
	public static function instance()
	{
		// Create the instance if it does not exist
		empty(self::$instance) AND new Head;

		return self::$instance;
	}

	public function __construct()
	{
		$this['title']      = new Head_Title;
		$this['base']       = new Head_Base;
		$this['meta']       = new Head_Meta;
		$this['css']        = new Head_Css;
		$this['javascript'] = new Head_Javascript;
		$this['link']       = new Head_Link;

		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);

		// Singleton instance
		self::$instance = $this;
	}

	public function __tostring()
	{
		return (string) $this->render();
	}

	public function render()
	{
		$html = '';
		foreach ($this as $field)
			$html .= $field->render();

		return $html;
	}
}

class Head_Partial extends Head_Core {

	public function __construct()
	{
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
	}

}

class Head_Meta extends Head_Partial {
	
	public function render()
	{
		$html = '';
		foreach ($this as $key => $value)
			$html .= html::meta($key, $value);
		
		return $html;
	}
	
}

class Head_Title extends Head_Partial {

	public function __construct($title = '')
	{
		$this['title'] = $title;
	}

	public function set($title)
	{
		$this['title'] = $title;
		return $this;
	}

	public function append($title)
	{
		$this['title'] .= ' &mdash; '.$title;
		return $this;
	}

	public function prepend($title)
	{
		$this['title'] = $title.' &mdash; '.$this['title'];
		return $this;
	}

	public function render()
	{
		if ($this['title'] != '')
			return (string) '<title>'.$this['title'].'</title>'."\n\r";

		return '';
	}
}

class Head_Base extends Head_Partial {

	public function __construct($base = '')
	{
		$this['base_href'] = $base;
	}

	public function set($base_href)
	{
		$this['base_href'] = $base_href;
		return $this;
	}

	public function render()
	{
		if ($this['base_href'] != '')
			return (string) '<base href="'.$this['base_href'].'" />'."\n\r";

		return '';
	}

}

class Head_Javascript extends Head_Partial {

	public function __construct()
	{
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
		$this['files']   = new Head_Javascript_File;
		$this['scripts'] = new Head_Js_Script;
	}

	public function append_file($file)
	{
		$this['files'][] = $file;
		return $this;
	}

	public function append_script($script)
	{
		$this['scripts'][] = $script;
		return $this;
	}

}

class Head_Javascript_File extends Head_Partial {

	public function render()
	{
		$html = '';
		foreach ($this as $field)
			$html .= html::script($field);

		return $html;
	}
}

class Head_Js_Script extends Head_Partial {

	public function render()
	{
		$html = '';
		foreach ($this as $script)
			$html .= '<script type="text/javascript">'.$script.'</script>'."\r\n";

		return $html;
	}

}

class Head_Css extends Head_Partial {

	public function __construct()
	{
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
		$this['files']  = new Head_Css_File;
		$this['styles'] = new Head_Css_Style;
	}

	public function append_file($file, $type = 'screen')
	{
		$this['files'][] = array($file, $type);
		return $this;
	}

	public function append_style($script)
	{
		$this['styles'][] = $script;
		return $this;
	}

}

class Head_Css_File extends Head_Partial {

	public function render()
	{
		$html = '';
		foreach ($this as $field)
			$html .= html::stylesheet($field[0], $field[1]);

		return $html;
	}

}

class Head_Css_Style extends Head_Partial {

	public function render()
	{
		$html = '';
		foreach ($this as $script)
			$html .= '<style type="text/css">'.$script.'</style>'."\r\n";

		return $html;
	}

}

class Head_Link extends Head_Partial {

	public function append($link, $rel = 'alternate', $type = 'application/rss+xml')
	{
		$this[] = array($link, $rel, $type);
		return $this;
	}

	public function render()
	{
		$html = '';
		foreach ($this as $link)
			$html .= html::link($link[0], $link[1], $link[2], NULL, NULL, TRUE);

		return $html;
	}

}