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
 * Model for the updates table.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Update_Model extends Model {
	/**
	 * Adds/Edits a update with the data specified in $data.
	 *
	 * If $uid is set to an ID of an update, it will update that update row.
	 *
	 * @param array $data	An array with field_name=>content for data to add.
	 * @param int	$uid	If not set to false, it will update the update row.
	 *
	 * @return int
	 */
	public function manage_update($data, $uid = FALSE)
	{
		$manage_update = $this->db;
		foreach ($data as $key => $value)
		{
			$manage_update->set($key, $value);
		}
		if ($uid == FALSE)
		{
			$result = $manage_update->insert('updates');

			// Log for newsfeeds.
			$newsfeed = $this->db->set(array(
				'uid' => $data['uid'],
				'upid' => $result->insert_id(),
				'pid' => $data['pid']
			))->insert('news');

			return $result->insert_id();
		}
		else
		{
			$manage_update->where('id', $uid);
			$manage_update->update('updates');

			return $uid;
		}
	}

	/**
	 * Adds a view to an update.
	 *
	 * @param int $uid The update id to add the view to.
	 *
	 * @return null
	 */
	public function view($uid)
	{
		$view = $this->db;
		$view = $view->set('views', new Database_Expression('views+1'));
		$view = $view->where('id', $uid);
		$view = $view->update('updates');
	}

	/**
	 * Checks if a user owns a update, or returns the uid of the one who does.
	 *
	 * @param int $upid The update ID of the update to check.
	 * @param int $uid The user ID of the user.
	 *
	 * @return mixed
	 */
	public function check_update_owner($upid, $uid = FALSE)
	{
		$db = $this->db;
		if ($uid === FALSE)
		{
			$find_owner = $db->from('updates')->where('id', $upid)->get()->current();
			return $find_owner->uid;
		}
		else
		{
			$check_owner = $db->from('updates')->where(array('uid' => $uid, 'id' => $upid))->get()->count();

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
	}

	/**
	 * Checks if an update exists.
	 *
	 * @param int $upid The update ID to check.
	 *
	 * @return bool
	 */
	public function check_update_exists($upid)
	{
		$db = $this->db;
		$check_exists = $db->from('updates')->where(array('id' => $upid))->get()->count();

		if ($check_exists >= 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Returns all the data about an update with the id $uid.
	 *
	 * @param int $uid
	 * 
	 * @return array
	 */
	public function update_information($uid)
	{
		$update_information = new Database();
		$update_information = $update_information->where('id', $uid);
		$update_information = $update_information->get('updates');
		$update_information = $update_information->result(FALSE);
		$update_information = $update_information->current();

		return $update_information;
	}

	/**
	 * Deletes an update.
	 *
	 * @param int $uid The update ID to delete.
	 *
	 * @return null
	 */
	public function delete_update($uid)
	{
		$delete_update = $this->db->where('id', $uid)->delete('updates');

		// Log for newsfeeds.
		$newsfeed = $this->db->where('upid', $uid)->delete('news');
	}

	/**
	 * Returns the number of updates in a project.
	 *
	 * @param int $pid The project ID.
	 * @param int $uid The user ID who owns the project.
	 *
	 * @return array
	 */
	public function project_updates($pid, $uid)
	{
		$db = $this->db;
		$count = $db->from('updates')->where(array('pid' => $pid, 'uid' => $uid))->get()->count();
		return $count;
	}

	/**
	 * Returns the total number of updates by a user.
	 *
	 * @param int $uid The user ID.
	 *
	 * @return int
	 */
	public function update_number($uid)
	{
		$db = $this->db;
		$count = $db->from('updates')->where(array('uid' => $uid))->get()->count();
		return $count;
	}

	/**
	 * Returns a random number of updates by a user.
	 *
	 * @param int $uid The user ID.
	 *
	 * @return int
	 */
	public function update_number_random($uid)
	{
		$db = $this->db;
		$get = $db->from('updates')->where(array('uid' => $uid))->orderby(NULL, 'RAND()')->limit(5)->get();
		return $get;
	}

	/**
	 * Returns the number of updates by a user within two dates.
	 *
	 * @param int $uid The user ID.
	 * @param int $start The start date.
	 * @param int $end The end date.
	 *
	 * @return int
	 */
	public function update_number_time($uid, $start, $end)
	{
		$db = $this->db;
		$count = $db->from('updates')->where(array('uid' => $uid, 'logtime <' => $end, 'logtime >=' => $start))->get()->count();
		return $count;
	}

	/**
	 * Returns the number of update views for a user within two dates.
	 *
	 * @param int $uid The user ID.
	 * @param int $start The start date.
	 * @param int $end The end date.
	 *
	 * @return int
	 */
	public function view_number_time($uid, $start, $end)
	{
		$count = 0;
		$db = $this->db;
		$updates = $db
			->from('updates')
			->where(array(
				'uid' => $uid,
				'logtime <' => $end,
				'logtime >=' => $start
			))->get();
		foreach($updates as $row)
		{
			$count = $count + $row->views;
		}
		return $count;
	}

	/**
	 * Returns with a list of newsfeed items for a user.
	 *
	 * @param int $uid The user ID.
	 *
	 * @return array
	 */
	public function news($uid)
	{
		$db = $this->db;

		// Let's start to build a query.
		$query = '';
		$first = TRUE;

		$tracks = $db->from('track')->where('uid', $uid)->get();
		$subscribes = $db->from('subscribe')->where('uid', $uid)->get();

		if ($tracks->count() || $subscribes->count())
		{
			foreach ($tracks as $row)
			{
				if ($first == TRUE)
				{
					$first = FALSE;
					$query = $query .'SELECT * FROM news WHERE uid='. $row->track;
				}
				else
				{
					$query = $query .' UNION SELECT * FROM news WHERE uid='. $row->track;
				}
			}

			foreach ($subscribes as $row)
			{

				if ($first == TRUE)
				{
					$first = FALSE;
					$query = $query .'SELECT * FROM news WHERE pid='. $row->pid;
				}
				else
				{
					$query = $query .' UNION SELECT * FROM news WHERE pid='. $row->pid;
				}
			}

			// Finish off the query.
			$query = $query .' ORDER BY logtime DESC LIMIT 8';
			$news = $db->query($query);
		}
		else
		{
			// no news? Blank array.
			$news = array();
		}
		return $news;
	}

	/**
	 * Returns the first and previous of an update in a project if it exists.
	 *
	 * @param int $id The id of the update.
	 * @param int $pid The pid of the project the update is part of.
	 *
	 * return mixed
	 */
	public function check_timeline_previous($id, $pid)
	{
		// Parse the timeline. Do we have a <<, <?

		// If the project is uncategorised, we have to limit it to the user's 
		// submissions, or only guest submissions.
		if ($pid == 1)
		{
			$uid = $this->update_information($id);
			$uid = $uid['uid'];
			$check = $this->db->from('updates')->where(array('pid' => $pid, 'uid' => $uid, 'id <' => $id))->orderby('id', 'DESC')->limit(1)->get();
		}
		else
		{
			$check = $this->db->from('updates')->where(array('pid' => $pid, 'id <' => $id))->orderby('id', 'DESC')->limit(1)->get();
		}

		$check_rows = $check->count();
		$check = $check->current();

		if ($check_rows > 0)
		{
			// Yep, we have a <, and therefore also a <<.
			$data['previous'] = $check->id;

			// ...and now for the <<.
			if (isset($uid))
			{
				$first = $this->db->from('updates')->where(array('pid' => $pid, 'uid' => $uid, 'id <' => $id))->orderby('id', 'ASC')->limit(1)->get()->current();
			}
			else
			{
				$first = $this->db->from('updates')->where(array('pid' => $pid, 'id <' => $id))->orderby('id', 'ASC')->limit(1)->get()->current();
			}

			$data['first'] = $first->id;

			return $data;
		}
		else
		{
			return NULL;
		}
	}

	/**
	 * Returns the last and next of an update in a project if it exists.
	 *
	 * @param int $id The id of the update.
	 * @param int $pid The pid of the project the update is part of.
	 *
	 * return mixed
	 */
	public function check_timeline_next($id, $pid)
	{
		// Parse the timeline. Do we have a >, >>?

		// If the project is uncategorised, we have to limit it to the user's 
		// submissions, or only guest submissions.
		if ($pid == 1)
		{
			$uid = $this->update_information($id);
			$uid = $uid['uid'];
			$check = $this->db->from('updates')->where(array('pid' => $pid, 'uid' => $uid, 'id >' => $id))->orderby('id', 'ASC')->limit(1)->get();
		}
		else
		{
			$check = $this->db->from('updates')->where(array('pid' => $pid, 'id >' => $id))->orderby('id', 'ASC')->limit(1)->get();
		}

		$check_rows = $check->count();
		$check = $check->current();

		if ($check_rows > 0)
		{
			// Yep, we have a >, and therefore also a >>.
			$data['next'] = $check->id;

			// ...and now for the >>.
			if (isset($uid))
			{
				$last = $this->db->from('updates')->where(array('pid' => $pid, 'uid' => $uid, 'id >' => $id))->orderby('id', 'DESC')->limit(1)->get()->current();
			}
			else
			{
				$last = $this->db->from('updates')->where(array('pid' => $pid, 'id >' => $id))->orderby('id', 'DESC')->limit(1)->get()->current();
			}

			$data['last'] = $last->id;

			return $data;
		}
		else
		{
			return NULL;
		}
	}
}
