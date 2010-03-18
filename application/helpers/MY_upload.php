<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Upload helper class for working with the global $_FILES
 * array and Validation library.
 *
 * $Id: upload.php 4134 2009-03-28 04:37:54Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class upload extends upload_Core {

	/**
	 * Save an uploaded file to a new location.
	 *
	 * @param   mixed    name of $_FILE input or array of upload data
	 * @param   string   new filename
	 * @param   string   new directory
	 * @param   integer  chmod mask
	 * @return  string   full path to new file
	 */
	public static function save($file, $filename = NULL, $directory = NULL, $chmod = 0644)
	{
		// Load file data from FILES if not passed as array
		$file = is_array($file) ? $file : $_FILES[$file];

		if ($filename === NULL)
		{
			// Use the default filename, with a timestamp pre-pended
			$filename = time().$file['name'];
		}

		if (Kohana::config('upload.remove_spaces') === TRUE)
		{
			// Remove spaces from the filename
			$filename = preg_replace('/[\s]+/', '_', $filename);
			$filename = preg_replace('/[^\w.-]+/', '_', $filename);
		}

		if ($directory === NULL)
		{
			// Use the pre-configured upload directory
			$directory = Kohana::config('upload.directory', TRUE);
		}

		// Make sure the directory ends with a slash
		$directory = rtrim($directory, '/').'/';

		if ( ! is_dir($directory) AND Kohana::config('upload.create_directories') === TRUE)
		{
			// Create the upload directory
			mkdir($directory, 0777, TRUE);
		}

		if ( ! is_writable($directory))
			throw new Kohana_Exception('upload.not_writable', $directory);

		if (is_uploaded_file($file['tmp_name']) AND move_uploaded_file($file['tmp_name'], $filename = $directory.$filename))
		{
			if ($chmod !== FALSE)
			{
				// Set permissions on filename
				chmod($filename, $chmod);
			}

			// Return new file path
			return $filename;
		}

		return FALSE;
	}
}
