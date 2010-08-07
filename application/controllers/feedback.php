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
 * Feedback controller for the interaction areas of the update system.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Feedback_Controller extends Core_Controller {
	/**
	 * Deletes a comment.
	 *
	 * @param int $cid The comment ID of the comment to delete.
	 *
	 * @return null
	 */
	public function delete($cid = FALSE)
	{	
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$update_model = new Update_Model;
		$comment_model = new Comment_Model;

		// Which update does this comment belong to?
		$comment_upid = $comment_model->comment_information($cid);
		$comment_upid = $comment_upid['upid'];

		// Who owns that update?
		$update_uid = $update_model->update_information($comment_upid);
		$update_uid = $update_uid['uid'];

		// First check if you own the comment.
		if (!empty($cid) && $comment_model->check_comment_owner($cid, $this->uid))
		{
			// Delete the comment.
			$comment_model->delete_comment($cid);
			url::redirect('updates/view/'. $comment_upid .'/');
		}
		// or if you own the update itself...
		elseif (!empty($cid) && $update_uid == $this->uid && $this->uid != 1)
		{
			// Delete the comment.
			$comment_model->delete_comment($cid);
			url::redirect('updates/view/'. $comment_upid .'/');
		}
		else
		{
			// Please ensure an ID is specified and you wrote the comment.
			throw new Kohana_User_Exception('', '', 'permissions_error');
		}
	}

	/**
	 * Adds a kudos to an update.
	 *
	 * @param int $uid The update ID to give the kudos to.
	 *
	 * @return null
	 */
	public function kudos($uid)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$update_model = new Update_Model;
		$kudos_model = new Kudos_Model;
		$user_model = new User_Model;

		// First check if they have already kudos'd the update and they don't 
		// own the update themselves...
		if ($kudos_model->check_kudos_owner($uid, $this->uid) || $update_model->check_update_owner($uid, $this->uid) || !$update_model->check_update_exists($uid))
		{
			// ... and back to the view page.
			$this->session->set('notification', 'Eh? Either you\'ve already kudos\'d it or you\'re not allowed to kudos your own stuff.');
			url::redirect(url::base() .'updates/view/'. $uid .'/');
		}
		else
		{
			// Add the kudos!
			$kudos_model->kudos($uid, $this->uid);

			// Send out email notifications.
			$update_info = $update_model->update_information($uid);
			$user_information = $user_model->user_information($update_info['uid']);
			if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
				$message = '<html><head><title>New WIPUP Kudos for you!</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has kudos\'d your update \'<a href="'. url::base() .'updates/view/'. $update_info['id'] .'/">'. $update_info['summary'] .'</a>\' on WIPUP.org. You can view their profile by clicking the link below:</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. url::base() .'profiles/view/'. $this->username .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
				$headers = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: wipup@wipup.org' . "\r\n" .
					'Reply-To: wipup@wipup.org' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				mail($user_information['email'], $this->username .' has kudos\'d your update on WIPUP', $message, $headers);
			}

			// ... and back to the view page.
			$this->session->set('notification', 'We\'ve added your kudos. You\'ve just made somebody happy. Good on you!');
			url::redirect(url::base() .'updates/view/'. $uid .'/');
		}
	}

	/**
	 * Subscribes the user to a project.
	 *
	 * @param int $pid The project ID to subscribe to.
	 *
	 * @return null
	 */
	public function subscribe($pid)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$update_model = new Update_Model;
		$project_model = new Project_Model;
		$subscribe_model = new Subscribe_Model;
		$track_model = new Track_Model;
		$user_model = new User_Model;

		$project_info = $project_model->project_information($pid);
		$project_uid = $project_info['uid'];

		// We cannot subscribe to somebody we are tracking.
		// First check if they have already subscribed themselves...
		if ($subscribe_model->check_project_subscriber($pid, $this->uid) || !$project_model->check_project_exists($pid) || $track_model->check_track_owner($project_uid, $this->uid) || $project_uid == $this->uid)
		{
			// Redirect to the project itself.
			$this->session->set('notification', 'Eh? You\'re already subscribed. Doesn\'t make sense to subscribe twice.');
			url::redirect(url::base() .'projects/view/'. $project_uid .'/'. $pid .'/');
		}
		else
		{
			// Subscribe the user!
			$subscribe_model->subscribe($pid, $this->uid);

			// Send out email notifications.
			$user_information = $user_model->user_information($project_uid);
			if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
				$message = '<html><head><title>New WIPUP Subscriber for you!</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has subscribed to your project \''. $project_info['name'] .'\' on WIPUP.org. You can view their profile by clicking the link below:</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. url::base() .'profiles/view/'. $this->username .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
				$headers = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: wipup@wipup.org' . "\r\n" .
					'Reply-To: wipup@wipup.org' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				mail($user_information['email'], $this->username .' has subscribed to your project on WIPUP', $message, $headers);
			}

			// Redirect to the project itself.
			$this->session->set('notification', 'You\'ll now receive a notification whenever something happens. It\'s like free spam!');
			url::redirect(url::base() .'projects/view/'. $project_uid .'/'. $pid .'/');
		}
	}

	/**
	 * Unsubscribes a project subscription.
	 *
	 * @param int $pid The pid to untrack.
	 *
	 * @return null
	 */
	public function unsubscribe($pid)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$subscribe_model = new Subscribe_Model;
		$project_model = new Project_Model;

		$project_information = $project_model->project_information($pid);

		if ($subscribe_model->check_project_subscriber($pid, $this->uid))
		{
			$subscribe_model->delete($pid, $this->uid);

			// Redirect to the project itself.
			$this->session->set('notification', 'You\'re now no longer subscribed. Plenty of other lonely projects wanting your attention you know?');
			url::redirect(url::base() .'projects/view/'. $project_information['uid'] .'/'. $pid .'/');
		}
		else
		{
			// Redirect to the project itself.
			$this->session->set('notification', 'Eh? You can\'t unsubscribe to something you were never subscribed to in the first place. Sheesh.');
			url::redirect(url::base() .'projects/view/'. $project_information['uid'] .'/'. $pid .'/');
		}
	}

	/**
	 * Makes a user track another user.
	 *
	 * @param int $uid The user ID to track.
	 *
	 * @return null
	 */
	public function track($uid)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$user_model = new User_Model;
		$track_model = new Track_Model;
		$subscribe_model = new Subscribe_Model;

		$user_info = $user_model->user_information($uid);

		// First check if they are already tracking the user...
		if ($track_model->check_track_owner($uid, $this->uid) || !$user_model->check_user_exists($uid) || $uid == $this->uid || $user_info['enable_tracking'] != 1)
		{
			$this->session->set('notification', 'Eh? You\'re already tracking '. $user_info['username'] .'. Can\'t stalk somebody twice, you know?');
			url::redirect(url::base() .'profiles/view/'. $user_info['username'] .'/');
		}
		else
		{
			// Check if they are subscribed to any of the user's projects.
			if ($track_model->check_user_subscribe($uid, $this->uid))
			{
				// Delete the subscribes.
				$delete = $track_model->check_user_subscribe($uid, $this->uid);
				foreach($delete as $row)
				{
					$subscribe_model->delete($row->pid, $row->uid);
				}
			}

			// Track the person!
			$track_model->track($uid, $this->uid);

			// Send out email notifications.
			$user_information = $user_model->user_information($uid);
			if (!empty($user_information['email']) && $user_information['notifications'] == 1) {
				$message = '<html><head><title>New WIPUP Tracker for you!</title></head><body><p>Dear '. $user_information['username'] .',</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. $this->username .'</a> has started tracking your account on WIPUP.org. You can view their profile by clicking the link below:</p><p><a href="'. url::base() .'profiles/view/'. $this->username .'/">'. url::base() .'profiles/view/'. $this->username .'/</a></p><p>You may turn of email notifications in your account options when logged in. Please do not reply to this email.</p><p>- The WIPUP Team</p></body></html>';
				$headers = 'MIME-Version: 1.0' . "\r\n" .
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					'From: wipup@wipup.org' . "\r\n" .
					'Reply-To: wipup@wipup.org' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				mail($user_information['email'], $this->username .' is now tracking you on WIPUP', $message, $headers);
			}

			$this->session->set('notification', 'You\'re now tracking '. $user_information['username'] .'. Ever realised how similar it is to stalking?');
			url::redirect(url::base() .'profiles/view/'. $user_information['username'] .'/');
		}
	}

	/**
	 * Untracks a user.
	 *
	 * @param int $uid The uid to untrack.
	 *
	 * @return null
	 */
	public function untrack($uid)
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$track_model = new Track_Model;
		$user_model = new User_Model;

		$user_information = $user_model->user_information($uid);

		if ($track_model->check_track_owner($uid, $this->uid))
		{
			$track_model->delete($uid, $this->uid);

			$this->session->set('notification', 'You\'ve stopped tracking '. $user_information['username'] .'. Find someone else to stalk now. Go on.');
			url::redirect(url::base() .'profiles/view/'. $user_information['username'] .'/');
		}
		else
		{
			$this->session->set('notification', 'Eh? You\'re were never tracking '. $user_information['username'] .' in the first place.');
			url::redirect(url::base() .'profiles/view/'. $user_information['username'] .'/');
		}
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
}
