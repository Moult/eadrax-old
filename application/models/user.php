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
 * @package		User
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * Core controller that everything uses.
 *
 * Sets some globally required methods and variables.
 *
 * @category	Eadrax
 * @package		User
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class User_Model extends Model {
	/**
	 * Adds a new basic user row.
	 *
	 * @return null
	 */
	public function add_user($username, $password)
	{
		$query = new Database();
		$query->set('username', $username);
		$query->set('password', md5($password));
		$query->insert('users');
	}

	/**
	 * Checks if a username is unique.
	 *
	 * TRUE if yes, else FALSE.
	 *
	 * @param string $username The username to check.
	 *
	 * @return bool
	 */
	public function unique_user_name($post)
	{
		$db = new Database();
		$count = $db->from('users')->where('username', $post['username'])->get()->count();
		if ($count >= 1)
		{
			$post->add_error('username', 'unique');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

}
