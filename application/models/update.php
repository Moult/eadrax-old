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
	 * @return null
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
			$manage_update->insert('updates');
		}
		else
		{
			$manage_update->where('id', $uid);
			$manage_update->update('updates');
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
		$delete_update = $this->db;
		$delete_update = $delete_update->where('id', $uid)->delete('updates');
	}
}
