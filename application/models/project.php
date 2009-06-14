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
 * @package		Project
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * Model for the projects table.
 *
 * @category	Eadrax
 * @package		Project
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Project_Model extends Model {
	/**
	 * Adds/Edits a project with the data specified in $data.
	 *
	 * Providing information for website, contributors, icon, id and logtime are 
	 * not necessary. If $pid is set to an ID of a project, it will update that 
	 * project row.
	 *
	 * @param array $data	An array with field_name=>content for data to add.
	 * @param int	$pid	If not set to false, it will update the project.
	 *
	 * @return null
	 */
	public function manage_project($data, $pid = FALSE)
	{
		$manage_project = $this->db;
		foreach ($data as $key => $value)
		{
			$manage_project->set($key, $value);
		}
		if ($pid == FALSE)
		{
			$manage_project->insert('projects');
		}
		else
		{
			$manage_project->where('id', $pid);
			$manage_project->update('projects');
		}
	}

	/**
	 * Checks if a user owns a project, or returns the uid of the one who does.
	 *
	 * @param int $pid The project ID of the project to check.
	 * @param int $uid The user ID of the user.
	 *
	 * @return mixed
	 */
	public function check_project_owner($pid, $uid = FALSE)
	{
		$db = $this->db;
		if ($uid === FALSE)
		{
			$find_owner = $db->from('projects')->where('id', $pid)->get()->current();
			return $find_owner->uid;
		}
		else
		{
			$check_owner = $db->from('projects')->where(array('uid' => $uid, 'id' => $pid))->get()->count();
			if ($check_owner >= 1)
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
	 * Deletes a project.
	 *
	 * @param int $pid The project ID to delete.
	 *
	 * @return null
	 */
	public function delete_project($pid)
	{
		$delete_project = $this->db;
		$delete_project = $delete_project->where('id', $pid)->delete('projects');
	}
}
