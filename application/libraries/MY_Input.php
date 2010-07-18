<?php
class Input extends Input_Core {
	public function __construct()
	{
		parent::__construct();
	}

	protected function search_array($array, $key, $default = NULL, $xss_clean = FALSE)
	{
		if ($key === array())
			return $array;

		if ( ! isset($array[$key]))
			return $default;

		// Get the value
		$value = $array[$key];

		if ($this->use_xss_clean === FALSE AND $xss_clean === TRUE)
		{
			// XSS clean the value
			$value = $this->xss_clean($value);
		}

		return html::specialchars($value);
	}
}
