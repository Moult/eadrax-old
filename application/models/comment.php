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
		$add_comment->insert('comments');
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
	 * Returns the number of comments by a user within two dates.
	 *
	 * @param int $uid The user ID.
	 * @param int $start The start date.
	 * @param int $end The end date.
	 *
	 * @return int
	 */
	public function comment_number_time($uid, $start, $end)
	{
		$db = $this->db;
		$count = $db->from('comments')->where(array('uid' => $uid, 'logtime <' => $end, 'logtime >=' => $start))->get()->count();
		return $count;
	}

	/**
	 * Returns the number of comments directed at a user within two dates.
	 *
	 * @param int $uid The user ID.
	 * @param int $start The start date.
	 * @param int $end The end date.
	 *
	 * @return int
	 */
	public function comment_for_number_time($uid, $start, $end)
	{
		$db = $this->db;
		$count = $db
			->from('comments')
			->where(array(
				'updates.uid' => $uid,
				'comments.uid !=' => $uid,
				'comments.logtime <' => $end,
				'comments.logtime >=' => $start
			))->join('updates', array(
				'comments.upid' => 'updates.id'
			))->get()->count();
		return $count;
	}
}
