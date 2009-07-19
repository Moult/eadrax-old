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
 * Model for the kudos table.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Kudos_Model extends Model {
	/**
	 * Checks if a user has already kudos'd an update.
	 *
	 * @param int $upid The update id to check.
	 * @param int $uid The user id to check.
	 *
	 * @return bool
	 */
	public function check_kudos_owner($upid, $uid)
	{
		$db = $this->db;
		$check_owner = $db->from('kudos')->where(array('uid' => $uid, 'upid' => $upid))->get()->count();

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
	 * Adds a kudos row or returns the number of kudos.
	 *
	 * @param int $upid The update ID.
	 * @param int $uid The user ID.
	 *
	 * @return mixed
	 */
	public function kudos($upid, $uid = FALSE)
	{
		if ($uid == FALSE)
		{
			$kudos = $this->db;
			$kudos = $kudos->from('kudos')->where(array('upid' => $upid))->get()->count();
			return $kudos;
		}
		else
		{
			$kudos = $this->db;
			$kudos->set('upid', $upid);
			$kudos->set('uid', $uid);
			$kudos->insert('kudos');
		}
	}

	/**
	 * Returns the total number of kudos a project has.
	 *
	 * @param int $pid The project ID.
	 *
	 * @return array
	 */
	public function kudos_project($pid)
	{
		$kudos = $this->db;
		$kudos = $kudos->select('kudos.upid')
			->from('updates')
			->where(array(
				'updates.pid' => $pid
			))->join('kudos', array(
				'kudos.upid' => 'updates.id'
			))->get();
		$count = $kudos->count();
		return $count;
	}
}
