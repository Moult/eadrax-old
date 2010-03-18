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
 * @package		Site
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Static main website pages, including the homepage.
 *
 * @category	Eadrax
 * @package		Site
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Site_Controller extends Core_Controller {
	/**
	 * This is the index page of the site.
	 *
	 * @return null
	 */
	public function index()
	{
		// Introduction Page.
		$introduction_view = new View('introduction');

		$this->template->content = array($introduction_view);
	}

	/**
	 * Page that shows legal information.
	 *
	 * @return null
	 */
	public function legal()
	{
		$legal_view = new View('legal');
		$this->template->content = array($legal_view);
	}

	public function version()
	{
		$version_view = new View('version');
		$this->template->content = array($version_view);
	}

	/**
	 * Standalone controller to upgrade from WIPUP 21.02.10a to 19.03.10a
	 *
	 * Upon uploading all files, run this function and it should update all 
	 * updates before the 19.03.10a functionality was added so that it is 
	 * compatible. Then delete this function - make sure it doesn't exist on a 
	 * live site or people can abuse it!
	 *
	 * Note that video thumbnails will not be perfect quality as they will be 
	 * built from reencoded flv files, not the original high quality video file. 
	 * However this is unavoidable.
	 *
	 * @return null
	 */
	public function upgrade()
	{
		echo '<div style="background-color: #FFF; border: 1px solid #000; padding: 10px; font-size: 14px;">';
		echo 'Running upgrades ... if errors occur please check that the file exists, check the backtrace, and report a bug if it is valid.<br />';
		$this->db = new Database();
		$query = $this->db->from('updates')->where(array('filename0 !=' => ''))->get();
		foreach ($query as $row) {
			$attachment_filename = $row->filename0;
			$extension = $row->ext0;
			$filename = DOCROOT .'uploads/files/'. $attachment_filename .'.'. $extension;

			if ($extension == 'gif' || $extension == 'jpg' || $extension == 'png' || $extension == 'avi' || $extension == 'mpg' || $extension == 'mov' || $extension == 'flv' || $extension == 'ogg' || $extension == 'wmv') {
				echo '... parsing update id '. $row->id .' with filename '. $attachment_filename .'.'. $extension .' ...';
			}

			// If it is an image, we need to thumbnail it.
			if ($extension == 'gif' || $extension == 'jpg' || $extension == 'png')
			{
				// Create a cropped thumbnail.
				list($width, $height, $type, $attr) = getimagesize($filename);

				if ($extension == 'jpg') {
					$myImage = imagecreatefromjpeg($filename);   
				} elseif ($extension == 'gif') {
					$myImage = imagecreatefromgif($filename);   
				} elseif ($extension == 'png') {
					$myImage = imagecreatefrompng($filename);   
				}
				  
				if ($width < $height*1.3) {  
					$cropWidth   = $width;   
					$cropHeight  = $width*.769;   
					$c1 = array("x"=>0, "y"=>($height-$cropHeight)/8);  
				} elseif ($width > $height) {  
					$cropWidth   = $height*1.3;   
					$cropHeight  = $height;   
					$c1 = array("x"=>($width-$cropWidth)/2, "y"=>0);  
				} else {
					$cropWidth   = $width;   
					$cropHeight  = $width*.769;   
					$c1 = array("x"=>0, "y"=>($height-$cropHeight)/8);  
				}

				// Creating the thumbnail  
				$thumb = imagecreatetruecolor(260, 200);   
				imagecopyresampled($thumb, $myImage, 0, 0, $c1['x'], $c1['y'], 260, 200, $cropWidth, $cropHeight);   
				   
				//final output    
				imagejpeg($thumb, DOCROOT .'uploads/icons/'. substr(basename($filename), 0, -4) .'_crop.jpg', 100);
				imagedestroy($thumb); 

				echo ' <span style="color: #005500;">SUCCESS</span> ...<br />';
			}

			if ($extension == 'avi' || $extension == 'mpg' || $extension == 'mov' || $extension == 'flv' || $extension == 'ogg' || $extension == 'wmv')
			{
				// Define files.
				$src_file  = $filename;
				$dest_file = DOCROOT .'uploads/files/'. $attachment_filename .'.flv';
				$dest_img  = DOCROOT .'uploads/icons/'. $attachment_filename .'.jpg';

				unlink($dest_img);

				// Define ffmpeg application path.
				$ffmpeg_path = Kohana::config('updates.ffmpeg_path');

				// Before snapshotting the video to make a 
				// thumbnail image, let's find out the length of 
				// the video.
				$ffmpeg_output = array();
				exec($ffmpeg_path ." -i ". escapeshellarg($src_file) ." 2>&1", $ffmpeg_output);

				// Search each line in the $ffmpeg_output.
				foreach ($ffmpeg_output as $key => $value)
				{
					if (preg_match('/Duration: [0-9]{2}:[0-9]{2}:[0-9]{2}/', $value, $matches))
					{
						// Now we are sure we have found the 
						// duration, get the value we need.
						$duration = substr($matches[0], 10);

						// Calculate the half-time.
						$duration_h = floor(substr($duration, 0, 2)/2);
						if ($duration_h%2 == 1)
						{
							$duration_m = floor((substr($duration, 3, 2)+60)/2);
						}
						else
						{
							$duration_m = floor(substr($duration, 3, 2)/2);
						}
						if ($duration_m%2 == 1)
						{
							$duration_s = floor((substr($duration, 6, 2)+60)/2);
						}
						else
						{
							$duration_s = floor(substr($duration, 6, 2)/2);
						}

						// Let's create the image.
						exec($ffmpeg_path ." -i ". escapeshellarg($src_file) ." -an -ss ". $duration_h .":". $duration_m .":". $duration_s ." -t 00:00:01 -r 1 -y ". escapeshellarg($dest_img));

						// Create a cropped thumbnail.
						list($width, $height, $type, $attr) = getimagesize($dest_img);
						$myImage = imagecreatefromjpeg($dest_img);   
						  
						if($width > $height)  {  
							$cropWidth   = $height*1.3;   
							$cropHeight  = $height;   
							$c1 = array("x"=>($width-$cropWidth)/2, "y"=>0);  
						} else {
							$cropWidth   = $width;   
							$cropHeight  = $width*.769;   
							$c1 = array("x"=>0, "y"=>($height-$cropHeight)/8);  
						}
						   
						// Creating the thumbnail  
						$thumb = imagecreatetruecolor(260, 200);   
						imagecopyresampled($thumb, $myImage, 0, 0, $c1['x'], $c1['y'], 260, 200, $cropWidth, $cropHeight);   
						   
						//final output    
						imagejpeg($thumb, DOCROOT .'uploads/icons/'. $attachment_filename .'_crop.jpg', 100);
						imagedestroy($thumb); 

						// Let's turn the image into a thumbnail.
						Image::factory($dest_img)->resize(80, 80, Image::AUTO)->save($dest_img);

						// We're done here.
						break;
					}
				}

				echo ' <span style="color: #005500;">SUCCESS</span> ...<br />';
			}

		}
		echo '... All '. $query->count() .' updates completed!<br /><br />';
		echo 'Congratulations! <strong>Remember to delete this function.</strong>';
		echo '</div>';

		die();
	}
}
