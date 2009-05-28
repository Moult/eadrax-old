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
	 * Set up routine.
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Adds a new basic user row.
	 *
	 * Returns TRUE if succesful, else FALSE.
	 *
	 * @return bool
	 */
	public function add_user($username, $password)
	{
		// Set the necessary data from the sources.
		$data = array(
			'username' => $username,
			'password' => md5($password),
			'lastactive' => time()
		);

		if ($this->db->insert('users', $data))
		{ 
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
