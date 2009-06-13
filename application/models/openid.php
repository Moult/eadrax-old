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
		$make_dummy_user = $this->db;
		$make_dummy_user->set('username', $username);
		$make_dummy_user->set('password', 'openid');
		$make_dummy_user->insert('users');

		// Find the UID of the user we just created.
		$uid = $this->db;
		$uid = $uid->where(array('username'=>$username,'password'=>'openid'));
		$uid = $uid->get('users');
		$uid = $uid->current();
		$uid = $uid->id;

		// Bind an OpenID account to the dummy user.
		$bind_openid = $this->db;
		$bind_openid->set('url', $openid);
		$bind_openid->set('uid', $uid);
		$bind_openid->insert('openid');
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
		$count = $db->from('openid')->where('url', $post['openid_url'])->get()->count();
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
		// Get the UID of the OpenID account.
		$openid_uid = $this->db;
		$openid_uid = $openid_uid->where(array('url'=>$openid));
		$openid_uid = $openid_uid->get('openid');
		$openid_uid = $openid_uid->current();
		$openid_uid = $openid_uid->uid;

		// Find the username with the corresponding UID.
		$username = $this->db;
		$username = $username->where(array('id'=>$openid_uid));
		$username = $username->get('users');
		$username = $username->current();

		return $username->username;
	}
}
