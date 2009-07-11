<?php
/**
 * Eadrax
 *
 * Eadrax is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Eadrax is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *                                                                                
 * You should have received a copy of the GNU General Public License
 * along with Eadrax; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @category	Eadrax
 * @package		Update
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Updates controller added for update system.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Updates_Controller extends Core_Controller {
	/**
	 * Adds a new update.
	 *
	 * @return null
	 */
	public function index()
	{
		// Logged in users and guest users will have different abilities when 
		// submitting updates to the website.
		if ($this->logged_in == TRUE)
		{
			// If the person is logged in...make sure they really are.
			$this->restrict_access();

			// Load necessary models.
			$update_model	= new Update_Model;
			$project_model	= new Project_Model;

			if ($this->input->post())
			{
				$summary	= $this->input->post('summary');
				$detail		= $this->input->post('detail');
				$pid		= $this->input->post('pid');

				// Let's first assume there is no file being uploaded.
				$attachment_filename = '';
				$extension = '';

				// Begin to validate the information.
				$validate = new Validation($this->input->post());
				$validate->pre_filter('trim');
				$validate->add_rules('summary', 'required', 'length[5, 70]', 'standard_text');
				$validate->add_rules('detail', 'standard_text');
				$validate->add_rules('pid', 'required', 'digit');
				$validate->add_callbacks('pid', array($this, '_validate_project_owner'));

				if ($validate->validate())
				{
					// Check whether or not we even have a file to validate.
					if (!empty($_FILES) && !empty($_FILES['attachment']['name']))
					{
						// Do not forget we need to validate the file.
						$files = new Validation($_FILES);
						$files = $files->add_rules('attachment', 'upload::valid', 'upload::type[gif,jpg,png,svg,tiff,bmp,exr,pdf,zip,rar,tar,tar.gz,tar.bz,ogg,wmv,mp3,wav,avi,mpg,mov,swf,flv,blend,xcf,doc,ppt,xls,odt,ods,odp,odg,psd,fla,ai,indd,aep]', 'upload::size[50M]');

						if ($files->validate())
						{
							// Upload the file as normal.
							$filename = upload::save('attachment', NULL, DOCROOT .'uploads/files/');
							$attachment_filename = basename($filename);

							// Let's determine what extension this file is.
							$extension = strtolower(substr(strrchr($_FILES['attachment']['name'], '.'), 1));

							// If it is an image, we need to thumbnail it.
							if ($extension == 'gif' || $extension == 'jpg' || $extension == 'png')
							{
								Image::factory($filename)->resize(80, 80, Image::WIDTH)->save(DOCROOT .'uploads/icons/'. basename($filename));
							}

							// If it is a video, we need to encode it.
							// HTML 5 is not out yet, so support goes through 
							// the FLV format. Oh well :)
							if ($extension == 'avi' || $extension == 'mpg' || $extension == 'mov' || $extension == 'flv' || $extension == 'ogg' || $extension == 'wmv')
							{
								// Define files.
								$src_file  = DOCROOT .'uploads/files/'. basename($filename);
								$dest_file = DOCROOT .'uploads/files/'. substr(basename($filename), 0, -3) .'flv';
								$dest_img  = DOCROOT .'uploads/icons/'. substr(basename($filename), 0, -3) .'jpg';

								// Define ffmpeg application path.
								$ffmpeg_path = Kohana::config('updates.ffmpeg_path');

								// If it is not already an FLV we need to encode it.
								if ($extension != 'flv') {
									$ffmpeg_obj = new ffmpeg_movie($src_file);

									// Needed function for next section.
									function make_multiple_two ($value)
									{
										$s_type = gettype($value/2); 

										if($s_type == "integer")
										{
											return $value;
										}
										else
										{
											return ($value-1);
										}
									}
									// Save needed variables for conversion.
									$src_width = make_multiple_two($ffmpeg_obj->getFrameWidth());
									$src_height = make_multiple_two($ffmpeg_obj->getFrameHeight());
									$src_fps = $ffmpeg_obj->getFrameRate();
									$src_ab = intval($ffmpeg_obj->getAudioBitRate()/1000);
									// Dion Moult: This is because flv only 
									// supports certain audio sample rates - or 
									// to the best of my knowledge they do.
									// $src_ar = $ffmpeg_obj->getAudioSampleRate();
									$src_ar = 44100;

									// Do the encoding!
									exec($ffmpeg_path ." -i ". $src_file ." -ar ". $src_ar ." -ab ". $src_ab ." -f flv -s ". $src_width ."x". $src_height ." ". $dest_file);

									// We will delete the original !.flv file 
									// to save space on the server. If they want 
									// to distribute a !.flv file let them host 
									// it elsewhere.
									unlink($src_file);
								}

								// Let's create the image.
								exec($ffmpeg_path ." -i ". $dest_file ." -an -ss 00:00:09 -t 00:00:01 -r 1 -y ". $dest_img);

								// Let's turn the image into a thumbnail.
								Image::factory($dest_img)->resize(80, 80, Image::WIDTH)->save($dest_img);
							}

						}
						else
						{
							die('Your upload has failed.'); # TODO dying is never good.
						}
					}
					// Everything went great! Let's add the update.
					$update_model->manage_update(array(
						'uid' => $this->uid,
						'summary' => $summary,
						'detail' => $detail,
						'pid' => $pid,
						'filename' => $attachment_filename,
						'ext' => $extension
					));

					// Then load our success view.
					$update_success_view = new View('update_success');

					// Then generate content.
					$this->template->content = array($update_success_view);
				}
				else
				{
					// Errors have occured. Fill in the form and set errors.
					$update_form_view = new View('update_form');
					$update_form_view->form = arr::overwrite(array(
						'summary' => '',
						'detail' => ''
					), $validate->as_array());
					$update_form_view->errors = $validate->errors('update_errors');

					// Set list of projects.
					$update_form_view->projects = $project_model->projects($this->uid);

					// Generate the content.
					$this->template->content = array($update_form_view);
				}
				// TODO
			}
			else
			{
				// Load the necessary view.
				$update_form_view = new View('update_form');

				// If we didn't press submit, we want a blank form.
				$update_form_view->form = array(
					'summary' => '',
					'detail' => ''
				);

				// Set list of projects.
				$update_form_view->projects = $project_model->projects($this->uid);

				// Generate the content.
				$this->template->content = array($update_form_view);
			}
		}
		else
		{
			// The person is a guest...
			// TODO
		}
	}

	/**
	 * Validates that the owner of a project is the logged in user.
	 *
	 */
	public function _validate_project_owner(Validation $array, $field)
	{
		$project_model = new Project_Model;

		$project_uid = $project_model->project_information($array[$field]);
		$project_uid = $project_uid['uid'];

		// We also allow the project uid to be 1, as this is a project owned by 
		// a guest - used for special universal projects.
		if ($project_uid != $this->uid && $project_uid != 1)
		{
			$array->add_error($field, 'project_owner');
		}
	}
}
