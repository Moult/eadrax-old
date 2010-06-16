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

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * Model for the comments table.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Comment_Model extends Model {

	/**
	 * Adds a comment with the data specified in $data.
	 *
	 * @param array $data	An array with field_name=>content for data to add.
	 *
	 * @return null
	 */
	public function add_comment($data)
	{
		$add_comment = $this->db;
		foreach ($data as $key => $value)
		{
			$add_comment->set($key, $value);
		}
		$result = $add_comment->insert('comments');

		// Log for newsfeeds.
		$pid = $this->db->from('updates')->where('id', $data['upid'])->get()->current()->pid;
		$newsfeed = $this->db->set(array(
			'uid' => $data['uid'],
			'cid' => $result->insert_id(),
			'upid' => $data['upid'],
			'pid' => $pid
		))->insert('news');
	}

	/**
	 * Deletes a comment.
	 *
	 * @param int $cid The comment ID to delete.
	 *
	 * @return null
	 */
	public function delete_comment($cid)
	{
		$delete_comment = $this->db;
		$delete_comment = $delete_comment->where('id', $cid)->delete('comments');

		// Log for newsfeeds.
		$newsfeed = $this->db->where('cid', $cid)->delete('news');
	}

	/**
	 * Checks if a user owns a comment.
	 *
	 * @param int $cid The comment ID of the comment to check.
	 * @param int $uid The user ID of the user.
	 *
	 * @return mixed
	 */
	public function check_comment_owner($cid, $uid = FALSE)
	{
		$db = $this->db;
		$check_owner = $db->from('comments')->where(array('uid' => $uid, 'id' => $cid))->get()->count();

		// $uid != 1 because guests cannot own anything.
		if ($check_owner >= 1 && $uid != 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Returns all the data about a comment with the id $cid.
	 *
	 * @param int $cid
	 * 
	 * @return array
	 */
	public function comment_information($cid)
	{
		$comment_information = new Database();
		$comment_information = $comment_information->where('id', $cid);
		$comment_information = $comment_information->get('comments');
		$comment_information = $comment_information->result(FALSE);
		$comment_information = $comment_information->current();

		return $comment_information;
	}

	/**
	 * Returns all the comments for an update with the id $uid.
	 *
	 * @param int $uid The update UID.
	 *
	 * @return array
	 */
	public function comment_update($uid)
	{
		$comment_update = $this->db->from('comments')->where('upid', $uid)->orderby('id', 'ASC')->get();
		return $comment_update;
	}

	/**
	 * Returns the number of comments for an update with the id $uid.
	 *
	 * @param int $uid The update UID.
	 *
	 * @return array
	 */
	public function comment_update_number($uid)
	{
		$comment_update_number = $this->db->from('comments')->where('upid', $uid)->get()->count();
		return $comment_update_number;
	}
}
