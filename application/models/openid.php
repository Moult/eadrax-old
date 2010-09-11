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
 * Model for the OpenID support table.
 *
 * @category	Eadrax
 * @package		User
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Openid_Model extends Model {
	/**
	 * Adds a new basic user row and binds an OpenID to it.
	 *
	 * @return null
	 */
	public function add_user($username, $openid)
	{
		$make_user = $this->db;
		$make_user->set('username', $username);
		$make_user->set('password', $openid);
		$result = $make_user->insert('users');

		return $result->insert_id();
	}

	/**
	 * Checks if an OpenID is unique.
	 *
	 * TRUE if yes, else FALSE.
	 *
	 * @param array $post		The array containing the OpenID to check.
	 * @param bool	$callback	If the function is used as a callback.
	 *
	 * @return bool
	 */
	public function unique_openid($post, $callback = TRUE)
	{
		$db = $this->db;
		$count = $db->from('users')->where('password', $post['openid_url'])->get()->count();
		if ($count >= 1)
		{
			if ($callback == TRUE)
			{
				$post->add_error('openid_url', 'unique');
			}
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * Returns the username of the account the OpenID is binded to.
	 *
	 * @param string $openid The OpenID URL.
	 *
	 * @return string
	 */
	public function get_openid_username($openid)
	{
		// Find the username with the corresponding UID.
		$username = $this->db;
		$username = $username->where('password', $openid);
		$username = $username->get('users');
		$username = $username->current();

		return $username->username;
	}
}
