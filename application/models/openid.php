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
	 * Adds a new basic user row.
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
		$uid = $uid->select('id');
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

}
