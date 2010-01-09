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
			$result = $manage_project->insert('projects');

			// Log for newsfeeds.
			$newsfeed = $this->db->set(array(
				'uid' => $data['uid'],
				'pid' => $result->insert_id()
			))->insert('news');
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

		// Log for newsfeeds.
		$newsfeed = $this->db->where('pid', $pid)->delete('news');
	}

	/**
	 * Returns an array with a list of categories.
	 *
	 * @return array
	 */
	public function categories()
	{
		$categories = $this->db;
		$categories = $categories->from('categories')->get();

		foreach ($categories as $category)
		{
			$category_list[$category->id] = $category->name;
		}
		return $category_list;
	}

	/**
	 * Returns all the data about a project with the id $pid.
	 *
	 * @param int $pid
	 * 
	 * @return array
	 */
	public function project_information($pid)
	{
		$project_information = new Database();
		$project_information = $project_information->where('id', $pid);
		$project_information = $project_information->get('projects');
		$project_information = $project_information->result(FALSE);
		$project_information = $project_information->current();

		return $project_information;
	}

	/**
	 * Returns an array with a list of projects owned by a user.
	 *
	 * @param int $uid The ID of the user who owns the projects.
	 *
	 * @return array
	 */
	public function projects($uid)
	{
		$projects = $this->db;
		$projects = $projects->from('projects')->where('uid', $uid)->orderby('logtime', 'DESC')->get();

		$project_list = array();

		// Add the special project "uncategorised".
		$project_list[1] = $this->project_information(1);
		$project_list[1] = $project_list[1]['name'];

		foreach ($projects as $project)
		{
			$project_list[$project->id] = $project->name;
		}

		return $project_list;
	}

	/**
	 * Checks if a project exists.
	 *
	 * @param int $pid The project ID to check.
	 *
	 * @return bool
	 */
	public function check_project_exists($pid)
	{
		$db = $this->db;
		$check_exists = $db->from('projects')->where(array('id' => $pid))->get()->count();

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
	 * Adds a view to a project.
	 *
	 * @param int $pid The project id to add the view to.
	 *
	 * @return null
	 */
	public function view($pid)
	{
		$view = $this->db;
		$view = $view->set('views', new Database_Expression('views+1'));
		$view = $view->where('id', $pid);
		$view = $view->update('projects');
	}

	/**
	 * Returns the number of project views for a user within two dates.
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
		$projects = $db
			->from('projects')
			->where(array(
				'uid' => $uid,
				'logtime <' => $end,
				'logtime >=' => $start
			))->get();
		foreach($projects as $row)
		{
			$count = $count + $row->views;
		}
		return $count;
	}
}
