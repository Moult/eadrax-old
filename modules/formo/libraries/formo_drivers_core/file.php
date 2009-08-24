<?php defined('SYSPATH') or die('No direct script access.');

class Formo_file_Driver extends Formo_Element {
	
	public $upload_path = 'uploads';
	public $allowed_types = 'jpg|png|gif';
	public $max_size = '1M';
	public $data = array();
	public $file_link_class = 'fileLink';
	
	private $_was_file;
	
	private $str;

	public function __construct($name='',$info=array())
	{
		parent::__construct($name, $info);
		
		$form = Formo::instance($this->formo_name);
		$form->set('open', preg_replace('/>/',' enctype="multipart/form-data">', $form->_open));
	}
	
	public function render()
	{
		return ($this->str) ? $this->str : '<input type="file" name="'.$this->name.'"'.Formo::quicktagss($this->_find_tags()).' />';
	}

	protected function validate_this()
	{
		$input = Input::instance();
		$fname = (isset($_FILES[$this->name.'_file'])) ? $this->name.'_file' : $this->name;
		$file = $_FILES[$fname];
		$already_uploaded = $input->post($this->name.'_path') ? TRUE : FALSE;
		$this->was_validated = TRUE;
				
		if ($this->required AND empty($file['name']) AND ! $already_uploaded)
		{
			if ($already_uploaded AND is_file($input->post($this->name.'_path')))
			{
				unlink($input->post($this->name.'_path'));
			}
			
			$this->error = $this->required_msg;
			return $this->error;
		}
		elseif ( ! $this->required AND ! $input->post($this->name) AND empty($file['name'])) // do nothing
		{
			return;
		}
		else // let's actually do something with this
		{
			$allowed_types = Formo::splitby($this->allowed_types);
			$time = time();
			
			// this means we're good with the file uploaded already
			if ($already_uploaded === TRUE AND ! $file['name'])
			{
				$full_path = $input->post($this->name.'_path');
				$path = array_pop(explode('/', $full_path));
				$file_name = $input->post($this->name);
			}
			elseif ($file['name']) // we're uploading a new one
			{
				// delete old entry
				if ($already_uploaded)
				{
					$full_path = $input->post($this->name.'_path');
					$path = array_pop(preg_split('/\//', $full_path));
					$file_name = $input->post($this->name);
					if (is_file($full_path)) unlink($full_path);
				}
				
				// start validating
				if ( ! upload::required($file))
					return $this->error = Kohana::lang('formo.invalidfile');
				
				if ( ! upload::size($file, array($this->max_size)))
					return $this->error = Kohana::lang('formo.too_large'). ' ('.$this->max_size.')';
					
				if ( ! upload::type($file, $allowed_types))
					return $this->error = Kohana::lang('formo.invalid_type');
				
								
				$full_path = upload::save($fname, $time.$file['name'], DOCROOT.$this->upload_path, 0777);
				$path = array_pop(preg_split('/\//', $full_path));
				$file_name = $file['name'];				
			}
						
			// fill $this->data with appropriate info
			$this->data['orig_name'] = $file_name;
			$this->data['file_name'] = end(preg_split('/\//', $full_path));
			$this->data['path'] = preg_replace('/\/'.$this->data['file_name'].'/', '', $full_path);
			$this->data['full_path'] = $full_path;
			$this->data['file_ext'] = strtolower(substr(strrchr($file_name, '.'), 1));
			$this->data['file_type'] = reset(Kohana::config('mimes.'.$this->data['file_ext']));
			$this->data['bytes'] = filesize($full_path);
			$this->data['file_size'] = round(filesize($full_path) / 1024, 2);
			$this->data['time'] = $time;
			
			if ($isize = getimagesize($full_path))
			{
				$this->data['is_image'] = 1;
				$this->data['image_width'] = $isize[0];
				$this->data['image_height'] = $isize[1];
				$this->data['image_size_str'] = $isize[3];
			}
			else
			{
				$this->data['is_image'] = 0;
				$this->data['image_width'] = NULL;
				$this->data['image_height'] = NULL;
				$this->data['image_size_str'] = NULL;
			}
			
			$this->value = $this->data;
			
			// create the extra stuff for saving past, accepted uploads and unvalidated forms
			$this->type = 'text';
			$this->_was_file = TRUE;
			$this->add_class($this->file_link_class);
			$this->value = $file_name;
			$this->readOnly = 'readOnly';
			$this->onClick = 'file_replace(\''.$this->id.'\')';

			$oldclose = $this->element_close;
			$class = ( ! empty($this->class)) ? ' class="'.preg_replace('/ *'.$this->file_link_class.'/', '', $this->class).'"' : '';
			
			$this->str = '<input type="text" name="'.$this->name.'" value="'.$this->value.'"'.Formo::quicktagss($this->_find_tags()).' />'
				  	   . '<script type="text/javascript">'."\n"
					   . 'function file_replace(id){'."\n"
					   . 'var txt = document.getElementById(id);'."\n"
					   . 'var file = document.getElementById(id+"_file");'."\n"
					   . 'txt.style.display = "none";'."\n"
					   . 'file.style.display = "inline";'."\n"
					   . '}'."\n"
					   . '</script>'."\n"
					   . '<input type="hidden" name="'.$this->name.'_path" id="'.$this->id.'_path" value="'.$full_path.'" />'."\n"
					   . '<input type="file" name="'.$this->name.'_file" id="'.$this->id.'_file"'.$class.' style="display:none"/>'."\n"
					   . $oldclose;
		}
	}
	
	public function clear()
	{
		$this->str = '';
		$this->data = array();
		$this->value = '';
	}
	
	public function get_value()
	{
		return $this->data;
	}

}