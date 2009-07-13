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
	 * TODO: This function demonstrates the use of the commenting ability.
	 *
	 * This will later be deleted to be integrated into the update view.
	 *
	 * @param int $uid The update ID to add the comment to.
	 *
	 * @return null
	 */
	public function add($uid)
	{
		// Load necessary models.
		$update_model = new Update_Model;
		$comment_model = new Comment_Model;

		// Does the uid even exist?
		if (!$update_model->check_update_exists($uid))
		{
			die('That update does not even exist!'); # TODO dying isn't good
		}

		if ($this->input->post())
		{
			$comment = $this->input->post('comment');

			// Validate the comment.
			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('comment', 'required', 'length[2, 400]', 'standard_text');

			if ($this->logged_in == FALSE)
			{
				$captcha = $this->input->post('captcha');
				$validate->add_callbacks('captcha', array($this, '_validate_captcha'));
			}

			if ($validate->validate())
			{
				$comment_model->add_comment(array(
					'uid' => $this->uid,
					'upid' => $uid,
					'comment' => $comment
				));

				// Load our success view.
				$comment_success_view = new View('comment_success');

				// Then generate content.
				$this->template->content = array($comment_success_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$comment_form_view = new View('comment_form');
				$comment_form_view->uid = $uid;
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
			$comment_form_view = new View('comment_form');
			$comment_form_view->uid = $uid;
			$comment_form_view->form = array(
				'comment' => ''
			);
			$this->template->content = array($comment_form_view);
		}
	}

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

		// First check if you own the comment.
		if (!empty($cid) && $comment_model->check_comment_owner($cid, $this->uid))
		{
			// Delete the comment.
			$comment_model->delete_comment($cid);
		}
		else
		{
			die('Please ensure an ID is specified and you wrote the comment.'); # TODO dying isn't good.
		}

		// Load views.
		$comment_delete_view = new View('comment_delete');

		// Generate the content.
		$this->template->content = array($comment_delete_view);
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

		// First check if they have already kudos'd the update and they don't 
		// own the update themselves...
		if ($kudos_model->check_kudos_owner($uid, $this->uid) || $update_model->check_update_owner($uid, $this->uid) || !$update_model->check_update_exists($uid))
		{
			// Load error view.
			$kudos_error_view = new View('kudos_error');

			// Generate the content.
			$this->template->content = array($kudos_error_view);
		}
		else
		{
			// Add the kudos!
			$kudos_model->kudos($uid, $this->uid);

			// Load success view.
			$kudos_success_view = new View('kudos_success');

			// Generate the content.
			$this->template->content = array($kudos_success_view);
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

		// First check if they have already subscribed themselves...
		if ($subscribe_model->check_project_subscriber($pid, $this->uid) || !$project_model->check_project_exists($pid))
		{
			// Load error view.
			$subscribe_error_view = new View('subscribe_error');

			// Generate the content.
			$this->template->content = array($subscribe_error_view);
		}
		else
		{
			// Add the kudos!
			$subscribe_model->subscribe($pid, $this->uid);

			// Load success view.
			$subscribe_success_view = new View('subscribe_success');

			// Generate the content.
			$this->template->content = array($subscribe_success_view);
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

		// First check if they are already tracking the user...
		if ($track_model->check_track_owner($uid, $this->uid) || !$user_model->check_user_exists($uid))
		{
			// Load error view.
			$track_error_view = new View('track_error');

			// Generate the content.
			$this->template->content = array($track_error_view);
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
					$subscribe_model->delete($row->id);
				}
			}

			// Track the person!
			$track_model->track($uid, $this->uid);

			// Load success view.
			$track_success_view = new View('track_success');

			// Generate the content.
			$this->template->content = array($track_success_view);
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
