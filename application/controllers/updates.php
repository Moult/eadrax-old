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
	 * Displays an update.
	 *
	 * @param int $uid The update ID to display.
	 *
	 * @return null
	 */
	public function view($uid)
	{
		// Load necessary models.
		$update_model		= new Update_Model;
		$user_model			= new User_Model;
		$comment_model		= new Comment_Model;
		$kudos_model		= new Kudos_Model;
		$subscribe_model	= new Subscribe_Model;
		$track_model		= new Track_Model;
		$project_model		= new Project_Model;

		// Hold on, does $uid even exist?
		if (!$update_model->check_update_exists($uid)) {
			Event::run('system.404');
		}

		// We have viewed the update, let's update the update statistics :D
		$update_model->view($uid);

		// Let's grab all the information we can about the update.
		$update_information = $update_model->update_information($uid);

		// Load the views.
		$update_view = new View('update');
		$comment_form_view = new View('comment_form');
		$random_update_view = new View('random_update');

		// The share view is slightly special as it'll be loaded inline.
		$update_information_view = new View('update_information');
		$update_information_view->uid = $uid;
		$update_information_view->summary = $update_information['summary'];
		$update_username = $user_model->user_information($update_information['uid']);
		$update_information_view->username = $update_username['username'];
		$update_view->share = $update_information_view;

		// All this information is very useful to the view, let's pass it on.
		foreach ($update_information as $key => $value)
		{
			$update_view->$key = $value;
		}

		// Do we have more than one file to deal with?
		$no_of_files = 0;
		for ($i=0; $i<5; $i++)
		{
			if (!empty($update_information['filename'. $i])) {
				$no_of_files++;
			}
		}

		$update_view->no_of_files = $no_of_files;

		// If we have files to deal with, let's get cracking!
		if ($no_of_files > 0)
		{
			for ($i=0; $i<5; $i++)
			{
				$update_view->{'filename_icon'. $i} = $this->_file_icon($update_information['filename'. $i], $update_information['ext'. $i]);

				// Find out generic information such as size, format, etc.
				$update_view->{'file_size'. $i} = filesize(DOCROOT .'uploads/files/'. $update_information['filename'. $i] .'.'.  $update_information['ext'. $i]);

				// What size to start off with?
				$update_view->{'file_size_ext'. $i} = 'bytes';

				if ($update_view->{'file_size'. $i} > 1024) {
					$update_view->{'file_size'. $i}     = $update_view->{'file_size'. $i} / 1024;
					$update_view->{'file_size_ext'. $i} = 'kb';
				}

				if ($update_view->{'file_size'. $i} > 1024) {
					$update_view->{'file_size'. $i}     = $update_view->{'file_size'. $i} / 1024;
					$update_view->{'file_size_ext'. $i} = 'Mb';
				}

				// Now to clean up the value we get.
				$update_view->{'file_size'. $i} = ceil($update_view->{'file_size'. $i});

				// How should we display the attachment?
				if (empty($update_information['filename'. $i]))
				{
					$update_view->{'display'. $i} = FALSE;
				}
				elseif ($update_information['ext'. $i] == 'jpg' || $update_information['ext'. $i] == 'png' || $update_information['ext'. $i] == 'gif')
				{
					$update_view->{'display'. $i} = 'image';
				}
				elseif ($update_information['ext'. $i] == 'avi' || $update_information['ext'. $i] == 'mpg' || $update_information['ext'. $i] == 'mov' || $update_information['ext'. $i] == 'flv' || $update_information['ext'. $i] == 'ogg' || $update_information['ext'. $i] == 'wmv')
				{
					$update_view->{'display'. $i} = 'video';
				}
				elseif ($update_information['ext'. $i] == 'mp3' || $update_information['ext'. $i] == 'wav')
				{
					$update_view->{'display'. $i} = 'sound';
				}
				else
				{
					$update_view->{'display'. $i} = 'download';
				}
			}
		}

		// Check if we can feature this update's project.
		if ($this->uid == $update_information['uid'] && $update_information['uid'] != 1) {
			if (isset($update_view->display0) && $update_view->display0 == 'image') {
				list($width, $height, $type, $attr) = getimagesize(DOCROOT .'uploads/files/'. $update_information['filename0'] .'.'. $update_information['ext0']);
				if ($width >= 808) {
					$update_view->feature = TRUE;
				}
			}
		}

		// Let's find out some random updates!
		if ($update_model->update_number($update_information['uid']) > 0)
		{
			$display_random = TRUE;
			$random_query = $update_model->update_number_random($update_information['uid']);
			$n = 0;
			$random_data = array();

			foreach ($random_query->result() as $row)
			{
				$random_data[$n]['summary'] = $row->summary;
				$random_data[$n]['id'] = $row->id;
				$random_data[$n]['uid'] = $row->uid;

				// Let's parse the thumbnails.
				$random_data[$n]['filename0'] = $this->_file_icon($row->filename0, $row->ext0);

                // Determind the sizes for offsetting.
                $path = substr($random_data[$n]['filename0'],strlen(url::base()));
                $path = DOCROOT . $path;
                $random_data[$n]['thumb_height'] = getimagesize($path);
                $random_data[$n]['thumb_width']  = $random_data[$n]['thumb_height'][0];
                $random_data[$n]['thumb_height'] = $random_data[$n]['thumb_height'][1];
                $random_data[$n]['thumb_offset'] = $random_data[$n]['thumb_height']/2;

                // Right, now find out the project name.
                if ($row->pid == 0) {
                    $random_data[$n]['project_name'] = 'Uncategorised';
                    $random_data[$n]['pid'] = 0;
                } else {
                    $random_data[$n]['pid'] = $row->pid;
					$random_data[$n]['project_name'] = $project_model->project_information($row->pid);
					$random_data[$n]['project_name'] = $random_data[$n]['project_name']['name'];
                }

                $n++;
			}

			// Send all the data we collected to the view...
			$random_update_view->random_data = $random_data;
			$random_update_view->user = $user_model->user_information($update_information['uid']);
		}

		// Send the comment view some useful information.
		$comment_form_view->uid = $uid;
		$comment_form_view->update_uid = $update_information['uid'];

		// Let's deal with comment submits first.
		if ($this->input->post())
		{
			$comment = $this->input->post('comment');

			// Validate the comment.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('comment', 'required', 'length[2, 1500]');

			if ($this->logged_in == FALSE)
			{
				$captcha = $this->input->post('captcha');
				$validate->add_callbacks('captcha', array($this, '_validate_captcha'));
			}

			if ($validate->validate())
			{
				// Let's add the comment!
				$comment_model->add_comment(array(
					'uid' => $this->uid,
					'upid' => $uid,
					'comment' => $comment
				));

				// Send out email notifications.
				$track_list = $track_model->track_list($this->uid);
				$subscribe_list = $subscribe_model->subscribe_list($update_information['pid']);

				$project_info = $project_model->project_information($update_information['pid']);

				$user_information = $user_model->user_information($update_information['uid']);
				if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
					$message = '<html><head><title>New WIPUP Comment for you!</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has commented your update entitled \''. $update_information['summary'] .'\' from the project \'<a href="'. url::base() .'projects/view/'. $this->uid .'/'. $project_info['id'] .'/">'. $project_info['name'] .'</a>\' on WIPUP.org. You can view this comment by clicking the link below:</p><p><a href="'. url::base() .'updates/view/'. $update_information['id'] .'/">'. url::base() .'updates/view/'. $update_information['id'] .'/</a></p><p>For your convenience, the comment is quoted below:<br /><span style="font-style: italic;">'. $comment .'</span></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
					$headers = 'MIME-Version: 1.0' . "\r\n" .
						'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
						'From: wipup@wipup.org' . "\r\n" .
						'Reply-To: wipup@wipup.org' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
					mail($user_information['email'], $this->username .' has commented on your update on WIPUP', $message, $headers);
				}

				foreach ($track_list as $tid) {
					$user_information = $user_model->user_information($tid);
					if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
						$message = '<html><head><title>New WIPUP Comment</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has commented on the update entitled \''. $update_information['summary'] .'\' from the project \'<a href="'. url::base() .'projects/view/'. $this->uid .'/'. $project_info['id'] .'/">'. $project_info['name'] .'</a>\' on WIPUP.org. You can view this comment by clicking the link below:</p><p><a href="'. url::base() .'updates/view/'. $update_information['id'] .'/">'. url::base() .'updates/view/'. $update_information['id'] .'/</a></p><p>For your convenience, the comment is quoted below:<br /><span style="font-style: italic;">'. $comment .'</span></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
						$headers = 'MIME-Version: 1.0' . "\r\n" .
							'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
							'From: wipup@wipup.org' . "\r\n" .
							'Reply-To: wipup@wipup.org' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						mail($user_information['email'], $this->username .' has made a new comment on WIPUP', $message, $headers);
					}
				}

				foreach ($subscribe_list as $sid) {
					$user_information = $user_model->user_information($sid);
					if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
						$message = '<html><head><title>New WIPUP Comment</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has commented on the update entitled \''. $update_information['summary'] .'\' from the project \'<a href="'. url::base() .'projects/view/'. $this->uid .'/'. $project_info['id'] .'/">'. $project_info['name'] .'</a>\' on WIPUP.org. You can view this comment by clicking the link below:</p><p><a href="'. url::base() .'updates/view/'. $update_information['id'] .'/">'. url::base() .'updates/view/'. $update_information['id'] .'/</a></p><p>For your convenience, the comment is quoted below:<br /><span style="font-style: italic;">'. $comment .'</span></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
						$headers = 'MIME-Version: 1.0' . "\r\n" .
							'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
							'From: wipup@wipup.org' . "\r\n" .
							'Reply-To: wipup@wipup.org' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						mail($user_information['email'], $this->username .' has made a new comment on WIPUP', $message, $headers);
					}
				}

				$comment_form_view->form = array(
					'comment' => ''
				);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$comment_form_view->form = arr::overwrite(array(
					'comment' => ''
				), $validate->as_array());
				$comment_form_view->errors = $validate->errors('comment_errors');

				// Generate the content.
				$this->template->content = array($comment_form_view);
			}
		}
		else
		{
			$comment_form_view->form = array(
				'comment' => ''
			);
		}

		// Let's load all the comments now we know all the comments are up to 
		// date and added successfully.
		$comment_form_view->comments = $comment_model->comment_update($uid);
		$comment_form_view->comment_total = $comment_model->comment_update_number($uid);

		// Store the appropriate user information for any user who has 
		// commented on the update.
		foreach ($comment_form_view->comments as $row)
		{
			$comment_var_name = 'comment'. $row->uid;
			$comment_form_view->$comment_var_name = $user_model->user_information($row->uid);
		}

		// Now let's start parsing information.

		// Parse some data about the update itself. Let's start with kudos.
		$update_view->kudos = $kudos_model->kudos($uid);

		// Now subscribing...
		$update_view->subscribed = $subscribe_model->check_project_subscriber($update_information['pid'], $this->uid);

		// Now tracking...
		$update_view->tracking = $track_model->check_track_owner($update_information['uid'], $this->uid);

		// Parse the user (creator of the update)
		if ($update_information['uid'] != 1)
		{
			$update_view->user_information = $user_model->user_information($update_information['uid']);
		}

		// Check if we can kudos this update.
		if ($kudos_model->check_kudos_owner($uid, $this->uid))
		{
			$update_view->kudos_error = TRUE;
		}

		// Parse the project.
		if ($update_information['pid'] != 1)
		{
			// Since we have an associated project, let's find out information 
			// about it.
			$update_view->project_information = $project_model->project_information($update_information['pid']);
		}

		// Parse the timeline.
		$check_previous = $update_model->check_timeline_previous($uid, $update_information['pid']);
		if (!empty($check_previous))
		{
			$update_view->first = $check_previous['first'];
			$update_view->previous = $check_previous['previous'];
		}
		$check_next = $update_model->check_timeline_next($uid, $update_information['pid']);
		if (!empty($check_next))
		{
			$update_view->next = $check_next['next'];
			$update_view->last = $check_next['last'];
		}

		// Parse the pastebin.
		if (!empty($update_information['pastebin']))
		{
			// Load the pastebin view.
			$pastebin_view = new View('pastebin');

			$geshi = new GeSHi(str_replace('&#039;', '\'', htmlspecialchars_decode($update_information['pastebin'])), $update_information['syntax']);
			$geshi->set_language_path(DOCROOT .'modules/geshi/resource/');
			$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
			$geshi->enable_classes();

			$pastebin_view->css = $geshi->get_stylesheet();
			$pastebin_view->pastebin = $geshi->parse_code();
			$pastebin_view->vanilla = $update_information['pastebin'];
			$pastebin_view->syntax = $update_information['syntax'];

			$display_pastebin = TRUE;
		}

        // Parse the description
        if (!empty($update_information['detail'])) {
			$simple_search = array(
				'/\[b\](.*?)\[\/b\]/is',
				'/\[i\](.*?)\[\/i\]/is',
				'/\[u\](.*?)\[\/u\]/is',
				'/\[url\=(.*?)\](.*?)\[\/url\]/is',
				'/\[url\](.*?)\[\/url\]/is',
				'/\[list\](.*?)\[\/list\]/is',
				'/\[\*\](.*?)\[\/\*\]/is'
			);
			 
			$simple_replace = array(
				'<strong>$1</strong>',
				'<em>$1</em>',
				'<span style="text-decoration: underline;">$1</span>',
				'<a href="$1">$2</a>',
				'<a href="$1">$1</a>',
				'<ul style="margin-left: 30px; font-size: 16px;">$1</ul>',
				'<li>$1</li>'
			);
			 
			$detail = preg_replace($simple_search, $simple_replace, $update_information['detail']);
			$detail = str_replace('&#039;', '\'', htmlspecialchars_decode($detail));

            $format = 'style="font-size:16px; margin-bottom: 10px;"';
            $detail = '<p '. $format .'>'. $detail .'</p>';
            $detail = preg_replace("/(?:\r?\n)+/", '</p><p '. $format .'>', $detail);

			// Let's do some really nasty fixing to maintain HTML validity.
			$detail = preg_replace(array('/<p '. $format .'><ul style="margin-left: 30px; font-size: 16px;"><\/p>/', '/<p '. $format .'><\/ul><\/p>/', '/<p '. $format .'><li>(.*?)<\/li><\/p>/'), array('<ul style="margin-left: 30px; font-size: 16px;">', '</ul>', '<li>$1</li>'), $detail);

            $update_view->detail = $detail;
        }

		// Generate the content.
		$content = array();
		$content[] = $update_view;
		if (isset($display_pastebin))
		{
			$content[] = $pastebin_view;
		}
		if (isset($display_random))
		{
			$content[] = $random_update_view;
		}
		$content[] = $comment_form_view;
		$this->template->content = $content;
	}

	/**
	 * Features an update.
	 *
	 * @param int $uid The uid to feature.
	 *
	 * @return null
	 */
	public function feature($uid)
	{
		$this->restrict_access();

		// Load necessary models.
		$update_model = new Update_Model;
		$user_model = new User_Model;

		// Check if we can feature this update's project.
		$update_information = $update_model->update_information($uid);
		if ($this->uid == $update_information['uid'] && $update_information['uid'] != 1) {
			if ($update_information['ext0'] == 'jpg' || $update_information['ext0'] == 'png' || $update_information['ext0'] == 'gif') {
				list($width, $height, $type, $attr) = getimagesize(DOCROOT .'uploads/files/'. $update_information['filename0'] .'.'. $update_information['ext0']);
				if ($width >= 808) {
					// Yep, we can feature it!
					$user_model->feature($update_information['uid'], $update_information['id']);
					$feature_view = new View('feature');
					$this->template->content = array($feature_view);
				}
			}
		}
	}

	/**
	 * Views a random update.
	 *
	 * @return null
	 */
	public function random()
	{
		$update_model = new Update_Model;

		$random_update = $update_model->update_number_random(FALSE, 1);
		foreach ($random_update as $row) {
			$upid = $row->id;
		}

		// Load that update!
		$this->view($upid);
	}

	/**
	 * Adds/edits an update.
	 *
	 * @param int $uid If update ID is specified, we will edit it instead of 
	 * adding a new update.
	 *
	 * @return null
	 */
	public function add($uid = FALSE)
	{
		// Load necessary models.
		$update_model	= new Update_Model;
		$project_model	= new Project_Model;
		$user_model		= new User_Model;
		$track_model	= new Track_Model;
		$subscribe_model= new Subscribe_Model;

		// If editing...
		if ($uid != FALSE)
		{
			// ... make sure they own the update,
			if (!$update_model->check_update_owner($uid, $this->uid))
			{
				die('You are not allowed to edit this update.');
			}
		}

		if ($this->input->post())
		{
			$summary	= $this->input->post('summary');
			$detail		= $this->input->post('detail');
			$syntax		= $this->input->post('syntax');
			$pastebin	= $this->input->post('pastebin');

			if ($this->logged_in == TRUE)
			{
				$pid = $this->input->post('pid');
			}
			else
			{
				$pid = 1;
			}

			if ($uid == FALSE)
			{
				// Let's first assume there is no file being uploaded.
				for ($i = 0; $i < 5; $i++)
				{
					${'attachment_filename'. $i} = '';
					${'extension'. $i} = '';
				}
			}
			else
			{
				$attachment_filename = $update_model->update_information($uid);
				$extension = $update_model->update_information($uid);
				for ($i = 0; $i < 5; $i++)
				{
					${'attachment_filename'. $i} = $attachment_filename['filename'. $i];
					${'extension'. $i} = $extension['ext'. $i];
				}
			}

			// Begin to validate the information.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('summary', 'required', 'length[5, 70]');
			$validate->add_callbacks('syntax', array($this, '_validate_syntax_language'));

			// If the person is logged in, validate project information.
			if ($this->logged_in == TRUE)
			{
				$validate->add_rules('pid', 'required', 'digit');
				$validate->add_callbacks('pid', array($this, '_validate_project_owner'));
			}
			else
			{
				// If not logged in, validate the CAPTCHA!
				$captcha = $this->input->post('captcha');
				$validate->add_callbacks('captcha', array($this, '_validate_captcha'));
			}

			if ($validate->validate())
			{
				// Do we even have a single file to validate?
				if (!empty($_FILES))
				{
					// Loop through each file to validate.
					for ($i = 0; $i < 5; $i++)
					{
						// Guests should only have one upload.
						if ($i == 0 || ($i > 0 && $this->logged_in == TRUE))
						{
							// Check whether or not we even have a file to validate.
							if (!empty($_FILES['attachment'. $i]['name']))
							{
								// If there is an existing file...
								if (!empty(${'attachment_filename'. $i}))
								{
									// ... Delete it!
									unlink(DOCROOT .'uploads/files/'. ${'attachment_filename'. $i} .'.'. ${'extension'. $i});

									if (file_exists(DOCROOT .'uploads/icons/'. ${'attachment_filename'. $i} .'.jpg'))
									{
										unlink(DOCROOT .'uploads/icons/'. ${'attachment_filename'. $i} .'.jpg');
									}

									if (file_exists(DOCROOT .'uploads/icons/'. ${'attachment_filename'. $i} .'_crop.jpg'))
									{
										unlink(DOCROOT .'uploads/icons/'. ${'attachment_filename'. $i} .'_crop.jpg');
									}

									if (file_exists(DOCROOT .'uploads/files/'. ${'attachment_filename'. $i} .'_fit.jpg'))
									{
										unlink(DOCROOT .'uploads/files/'. ${'attachment_filename'. $i} .'_fit.jpg');
									}
								}

								// The upload size limit will be different for guests and normal users.
								if ($this->logged_in == TRUE)
								{
									$size_limit = Kohana::config('updates.user_upload_limit');
								}
								else
								{
									$size_limit = Kohana::config('updates.guest_upload_limit');
								}

								// Do not forget we need to validate the file.
								$files = new Validation($_FILES);
								$files = $files->add_rules('attachment'. $i, 'upload::valid', 'upload::type['. Kohana::config('updates.filetypes') .']', 'upload::size['. $size_limit .']');

								if ($files->validate())
								{
									// Upload the file as normal.
									$filename = upload::save('attachment'. $i, time() . strtolower($_FILES['attachment'. $i]['name']), DOCROOT .'uploads/files/');

									// Let's determine what extension this file is.
									$extension = strtolower(substr(strrchr($_FILES['attachment'. $i]['name'], '.'), 1));

									// Now determine the file name.
									$attachment_filename = substr(basename($filename), 0, -strlen($extension)-1);

									// If it is an image, we need to thumbnail it.
									if ($extension == 'gif' || $extension == 'jpg' || $extension == 'png')
									{
										// If the width is greater than the layout width...
										list($width, $height, $type, $attr) = getimagesize($filename);
										if ($width > Kohana::config('updates.fit_width'))
										{
											// ... we need to resize it.
											Image::factory($filename)->resize(Kohana::config('updates.fit_width'), Kohana::config('updates.fit_height'), Image::WIDTH)->save(DOCROOT .'uploads/files/'. substr(basename($filename), 0, -4) .'_fit.jpg');
										}

										Image::factory($filename)->resize(80, 80, Image::AUTO)->save(DOCROOT .'uploads/icons/'. substr(basename($filename), 0, -3) .'jpg');

										// Create a cropped thumbnail.
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
									}

									// If it is a video, we need to encode it.
									// HTML 5 is not out yet, so support goes through 
									// the FLV format. Oh well :)
									if ($extension == 'avi' || $extension == 'mpg' || $extension == 'mov' || $extension == 'flv' || $extension == 'ogg' || $extension == 'wmv' || $extension == 'mp4')
									{
										// Define files.
										$src_file  = DOCROOT .'uploads/files/'. basename($filename);
										$dest_file = DOCROOT .'uploads/files/'. substr(basename($filename), 0, -3) .'flv';
										$dest_img  = DOCROOT .'uploads/icons/'. substr(basename($filename), 0, -3) .'jpg';

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
												imagejpeg($thumb, DOCROOT .'uploads/icons/'. substr(basename($filename), 0, -4) .'_crop.jpg', 100);
												imagedestroy($thumb); 

												// Let's turn the image into a thumbnail.
												Image::factory($dest_img)->resize(80, 80, Image::AUTO)->save($dest_img);

												// We're done here.
												break;
											}
										}

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
											//$src_ab = intval($ffmpeg_obj->getAudioBitRate()/1000);
											$src_ab = 56;
											// Dion Moult: This is because flv only 
											// supports certain audio sample rates - or 
											// to the best of my knowledge they do.
											// $src_ar = $ffmpeg_obj->getAudioSampleRate();
											$src_ar = 44100;

											// Do the encoding!
											exec($ffmpeg_path ." -i ". escapeshellarg($src_file) ." -ar ". $src_ar ." -ab ". $src_ab ." -f flv -s ". $src_width ."x". $src_height ." ". escapeshellarg($dest_file));

											// Now our filetype extension has changed!
											$extension = 'flv';

											// We will delete the original !.flv file 
											// to save space on the server. If they want 
											// to distribute a !.flv file let them host 
											// it elsewhere.
											unlink($src_file);
										}

									}
									// Reset the variables from generic to specific
									${'attachment_filename'. $i} = $attachment_filename;
									${'extension'. $i} = $extension;
								}
								else
								{
									die('Your upload has failed.'); # TODO dying is never good.
								}
							}
						}
					}
				}

				// Let's clear up empty upload fields.
				$attachment_array = array();
				$extension_array = array();

				for ($i=0; $i<5; $i++)
				{
					if (!empty(${'attachment_filename'. $i}))
					{
						$attachment_array[] = ${'attachment_filename'. $i};
						$extension_array[] = ${'extension'. $i};
					}
				}

				// Then clear up the leftover possible array values
				for ($i=0; $i<5; $i++)
				{
					if (empty($attachment_array[$i])) {
						$attachment_array[$i] = '';
						$extension_array[$i] = '';
					}
				}

				if ($uid == FALSE)
				{
					// Everything went great! Let's add the update.
					$uid = $update_model->manage_update(array(
						'uid' => $this->uid,
						'summary' => $summary,
						'detail' => $detail,
						'pid' => $pid,
						'filename0' => $attachment_array[0],
						'filename1' => $attachment_array[1],
						'filename2' => $attachment_array[2],
						'filename3' => $attachment_array[3],
						'filename4' => $attachment_array[4],
						'ext0' => $extension_array[0],
						'ext1' => $extension_array[1],
						'ext2' => $extension_array[2],
						'ext3' => $extension_array[3],
						'ext4' => $extension_array[4],
						'pastebin' => $pastebin,
						'syntax' => $syntax
					));

					// Bump up the project.
					$project_model->manage_project(array(
						'logtime' => new Database_Expression('NOW()')
					), $pid);

					$project_info = $project_model->project_information($pid);

					// Send out email notifications.
					$track_list = $track_model->track_list($this->uid);
					$subscribe_list = $subscribe_model->subscribe_list($pid);

					foreach ($track_list as $tid) {
						$user_information = $user_model->user_information($tid);
						if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
							$message = '<html><head><title>New WIPUP Update</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has created a new update entitled \''. $summary .'\' from the project \'<a href="'. url::base() .'projects/view/'. $this->uid .'/'. $pid .'/">'. $project_info['name'] .'</a>\' on WIPUP.org. You can view this update by clicking the link below:</p><p><a href="'. url::base() .'updates/view/'. $uid .'/">'. url::base() .'updates/view/'. $uid .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
							$headers = 'MIME-Version: 1.0' . "\r\n" .
								'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
								'From: wipup@wipup.org' . "\r\n" .
								'Reply-To: wipup@wipup.org' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();
							mail($user_information['email'], $this->username .' has a new update on WIPUP', $message, $headers);
						}
					}

					foreach ($subscribe_list as $sid) {
						$user_information = $user_model->user_information($sid);
						if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
							$message = '<html><head><title>New WIPUP Update</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has created a new update entitled \''. $summary .'\' from the project \'<a href="'. url::base() .'projects/view/'. $this->uid .'/'. $pid .'/">'. $project_info['name'] .'</a>\' on WIPUP.org. You can view this update by clicking the link below:</p><p><a href="'. url::base() .'updates/view/'. $uid .'/">'. url::base() .'updates/view/'. $uid .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
							$headers = 'MIME-Version: 1.0' . "\r\n" .
								'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
								'From: wipup@wipup.org' . "\r\n" .
								'Reply-To: wipup@wipup.org' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();
							mail($user_information['email'], $this->username .' has a new update on WIPUP', $message, $headers);
						}
					}
				}
				else
				{
					// Everything went great! Let's edit the update.
					$update_model->manage_update(array(
						'uid' => $this->uid,
						'summary' => $summary,
						'detail' => $detail,
						'pid' => $pid,
						'filename0' => $attachment_array[0],
						'filename1' => $attachment_array[1],
						'filename2' => $attachment_array[2],
						'filename3' => $attachment_array[3],
						'filename4' => $attachment_array[4],
						'ext0' => $extension_array[0],
						'ext1' => $extension_array[1],
						'ext2' => $extension_array[2],
						'ext3' => $extension_array[3],
						'ext4' => $extension_array[4],
						'pastebin' => $pastebin,
						'syntax' => $syntax
					), $uid);
				}

				// Then load our success view.
				$update_success_view = new View('update_success');

				// Pass some useful information.
				$update_success_view->uid = $uid;

				// Then generate content.
				$this->template->content = array($update_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$update_form_view = new View('update_form');
				$update_form_view->form = arr::overwrite(array(
					'summary' => '',
					'detail' => '',
					'pastebin' => '',
					'syntax' => '',
					'pid' => ''
				), $validate->as_array());
				$update_form_view->errors = $validate->errors('update_errors');

				if ($uid != FALSE)
				{
					$update_form_view->uid = $uid;
				}

				// Set list of projects.
				$update_form_view->projects = $project_model->projects($this->uid);

				// We need to add contributor projects.
				$update_form_view->contributor_projects = $project_model->contributor_projects($this->username);

				// Set list of syntax highlight options.
				$update_form_view->languages = Kohana::config('updates.languages');

				// Generate the content.
				$this->template->content = array($update_form_view);
			}
		}
		else
		{
			// Load the necessary view.
			$update_form_view = new View('update_form');

			if ($uid == FALSE)
			{
				// If we didn't press submit, we want a blank form.
				$update_form_view->form = array(
					'summary' => '',
					'detail' => '',
					'pastebin' => '',
					'syntax' => '',
					'pid' => ''
				);
			}
			else
			{
				$update_form_view->form = $update_model->update_information($uid);
				$update_form_view->uid = $uid;
			}

			// Set list of projects.
			$update_form_view->projects = $project_model->projects($this->uid);

			// We need to add contributor projects.
			$update_form_view->contributor_projects = $project_model->contributor_projects($this->username);

			// Set list of syntax highlight options.
			$update_form_view->languages = Kohana::config('updates.languages');

			// Generate the content.
			$this->template->content = array($update_form_view);
		}
	}

	/**
	 * Validates that the owner of a project is the logged in user.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_project_owner(Validation $array, $field)
	{
		$project_model = new Project_Model;

		$project_uid = $project_model->project_information($array[$field]);
		$project_uid = $project_uid['uid'];

		$contributor_projects = $project_model->contributor_projects($this->username);

		// We also allow the project uid to be 1, as this is a project owned by 
		// a guest - used for special universal projects.
		if ($project_uid != $this->uid && $project_uid != 1 && !array_key_exists($array[$field], $contributor_projects))
		{
			$array->add_error($field, 'project_owner');
		}
	}

	/**
	 * Validates that there is actually a possible syntax highlighter for the 
	 * language that the user has chosen.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_syntax_language(Validation $array, $field)
	{
		if (!array_key_exists($array[$field], Kohana::config('updates.languages')))
		{
			$array->add_error($field, 'syntax_language');
		}
	}

	/**
	 * Deletes an update.
	 *
	 * @param int $uid The update ID of the update to delete.
	 *
	 * @return null
	 */
	public function delete($uid = FALSE)
	{	
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$update_model = new Update_Model;

		// First check if you own the update.
		if (!empty($uid) && $update_model->check_update_owner($uid, $this->uid))
		{
			// Determine the value of the updates's attached file.
			$fileinfo	= $update_model->update_information($uid);
			for ($i = 0; $i < 5; $i++)
			{
				// Is there an existing attachment?
				if (!empty($fileinfo['filename'. $i]))
				{
					// Delete the file.
					unlink(DOCROOT .'uploads/files/'. $fileinfo['filename'. $i] .'.'. $fileinfo['ext'. $i]);
					if (file_exists(DOCROOT .'uploads/icons/'. $fileinfo['filename'. $i] .'.jpg'))
					{
						unlink(DOCROOT .'uploads/icons/'. $fileinfo['filename'. $i] .'.jpg');
					}

					if (file_exists(DOCROOT .'uploads/icons/'. $fileinfo['filename'. $i] .'_crop.jpg'))
					{
						unlink(DOCROOT .'uploads/icons/'. $fileinfo['filename'. $i] .'_crop.jpg');
					}

					if (file_exists(DOCROOT .'uploads/files/'. $fileinfo['filename'. $i] .'_fit.jpg'))
					{
						unlink(DOCROOT .'uploads/files/'. $fileinfo['filename'. $i] .'_fit.jpg');
					}
				}
			}

			// Delete the update.
			$update_model->delete_update($uid);
		}
		else
		{
			die('Please ensure an ID is specified and you own the update.'); # TODO dying isn't good.
		}

		// Load views.
		$update_delete_view = new View('update_delete');

		// Generate the content.
		$this->template->content = array($update_delete_view);
	}

	/**
	 * Validates a captcha.
	 *
	 * @param Validation $array The array containing validation information.
	 * @param $field The key for the value.
	 *
	 * @return null
	 */
	public function _validate_captcha(Validation $array, $field)
	{
		$this->securimage = new Securimage;

		if ($this->securimage->check($array[$field]) == FALSE)
		{
			$array->add_error($field, 'captcha');
		}
	}

	/**
	 * Returns the url of the icon to use for a filetype.
	 *
	 * @param string $ext The file extension
	 *
	 * @return string
	 */
	public function _file_icon($filename, $ext, $cropped = FALSE)
	{
		if ($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'avi' || $ext == 'mpg' || $ext == 'mov' || $ext == 'flv' || $ext == 'mp4') { 
			if ($cropped == TRUE) {
				return url::base() .'uploads/icons/'. $filename .'_crop.jpg';
			} else {
				return url::base() .'uploads/icons/'. $filename .'.jpg';
			}
		} elseif (empty($filename)) {
			// If there is no filename, we give it a special icon.
			return url::base() .'images/icons/newspaper_48.png';
		} else {
			// And icons for the rest!
			if ($ext == 'zip' || $ext == 'rar' || $ext == 'gz' || $ext == 'bz') {
				return url::base() .'images/icons/floppy_disk_48.png';
			} elseif ($ext == 'svg' || $ext == 'tiff' || $ext == 'bmp' || $ext == 'exr') {
				return url::base() .'images/icons/image_48.png';
			} elseif ($ext == 'doc' || $ext == 'odt') {
				return url::base() .'images/icons/paper_content_pencil_48.png';
			} elseif ($ext == 'ppt' || $ext == 'odp' || $ext == 'odg') {
				return url::base() .'images/icons/paper_content_chart_48.png';
			} elseif ($ext == 'xls' || $ext == 'ods') {
				return url::base() .'images/icons/table_48.png';
			} elseif ($ext == 'pdf') {
				return url::base() .'images/icons/paper_content_48.png';
			} elseif ($ext == 'wav' || $ext == 'mp3') {
				return url::base() .'images/icons/blue_speech_bubble_48.png';
			} else {
				return url::base() .'images/icons/box_download_48.png';
			}
		}
	}
}
