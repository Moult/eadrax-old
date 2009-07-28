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
 * Model for the track table.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Track_Model extends Model {
	/**
	 * Checks if a user has already tracked a user.
	 *
	 * @param int $tuid The tracked user id to check.
	 * @param int $uid The user id to check.
	 *
	 * @return bool
	 */
	public function check_track_owner($tuid, $uid)
	{
		$db = $this->db;
		$check_owner = $db->from('track')->where(array('uid' => $uid, 'track' => $tuid))->get()->count();

		if ($check_owner >= 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Adds a track row or returns the number of tracks.
	 *
	 * @param int $tuid The tracked user ID.
	 * @param int $uid The user ID.
	 *
	 * @return mixed
	 */
	public function track($tuid, $uid = FALSE)
	{
		if ($uid == FALSE)
		{
			$track = $this->db;
			$track = $track->from('track')->where(array('track' => $tuid))->get()->count();
			return $track;
		}
		else
		{
			$track = $this->db;
			$track->set('track', $tuid);
			$track->set('uid', $uid);
			$track->insert('track');

			// Log for newsfeeds.
			$newsfeed = $this->db->set(array(
				'uid' => $data['uid'],
				'tid' => $data['tuid']
			))->insert('news');
		}
	}

	/**
	 * Returns a list of people who are tracking a user.
	 *
	 * @param int $uid The user ID.
	 *
	 * @return array
	 */
	public function track_list($uid)
	{
		$track = $this->db;
		$track = $track->from('track')->where(array('track' => $uid))->orderby('id', 'DESC')->get();
		$track_list = array();

		foreach($track as $row)
		{
			$track_list[] = $row->uid;
		}

		return $track_list;
	}

	/**
	 * Checks if a user has subscribed to any of another user's projects.
	 *
	 * @param int $tuid The uid of the projects owner.
	 * @param int $uid The uid of the subscriber.
	 *
	 * @return int
	 */
	public function check_user_subscribe($tuid, $uid)
	{
		$check = $this->db;
		$check = $check->select('subscribe.uid', 'subscribe.pid')
			->from('projects')
			->where(array(
			'projects.uid' => $tuid,
			'subscribe.uid' => $uid
		))->join('subscribe', array(
			'projects.id' => 'subscribe.pid'
		))->get();
		$count = $check->count();
		if ($count >= 1)
		{
			return $check;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Deletes a track row.
	 *
	 * @param int $tuid The tracked ID.
	 * @param int $uid The user ID.
	 *
	 * @return null
	 */
	public function delete($tuid, $uid)
	{
		$delete = $this->db;
		$delete = $delete->where(array('track' => $tuid, 'uid' => $uid))->delete('track');

		// Log for newsfeeds.
		$newsfeed = $this->db->where(array('uid' => $uid, 'tid' => $tuid))->delete('news');
	}
}
