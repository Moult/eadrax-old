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
 * Model for the subscribe table.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Subscribe_Model extends Model {
	/**
	 * Checks if a user has already subscribed to a project.
	 *
	 * @param int $pid The project id to check.
	 * @param int $uid The user id to check.
	 *
	 * @return bool
	 */
	public function check_project_subscriber($pid, $uid)
	{
		$db = $this->db;
		$check_subscriber = $db->from('subscribe')->where(array('pid' => $pid, 'uid' => $uid))->get()->count();

		if ($check_subscriber >= 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Adds a subscribe row or returns the number of subscribers.
	 *
	 * @param int $pid The subscribe ID.
	 * @param int $uid The user ID.
	 *
	 * @return mixed
	 */
	public function subscribe($pid, $uid = FALSE)
	{
		if ($uid == FALSE)
		{
			$subscribers = $this->db;
			$subscribers = $subscribers->from('subscribe')->where(array('pid' => $pid))->get()->count();
			return $subscribers;
		}
		else
		{
			$subscribe = $this->db;
			$subscribe->set('pid', $pid);
			$subscribe->set('uid', $uid);
			$subscribe->insert('subscribe');
		}
	}

	/**
	 * Returns a list of people who are subscribed to a project.
	 *
	 * @param int $pid The project ID.
	 *
	 * @return array
	 */
	public function subscribe_list($pid)
	{
		$subscribe = $this->db;
		$subscribe = $subscribe->from('subscribe')->where(array('pid' => $pid))->orderby('id', 'DESC')->get();
		$subscribe_list = array();

		foreach($subscribe as $row)
		{
			$subscribe_list[] = $row->uid;
		}

		return $subscribe_list;
	}

	/**
	 * Deletes a subscribe row.
	 *
	 * @param int $pid The project ID.
	 * @param int $uid The user ID.
	 *
	 * @return null
	 */
	public function delete($pid, $uid)
	{
		$delete = $this->db;
		$delete = $delete->where(array('pid' => $pid, 'uid' => $uid))->delete('subscribe');
	}
}
