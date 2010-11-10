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
 * @package		API
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

defined('SYSPATH') or die('No direct script access.');

/**
 *
 * Model for the users table.
 *
 * @category	Eadrax
 * @package		API
 * @subpackage	Models
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Api_Model extends Model {
	/**
	 * Adds a new basic traffic row.
	 *
	 * @return int
	 */
	public function add_traffic($ip)
	{
		$ip = ip2long($ip);
		$count = $this->count_traffic($ip);

		$add_traffic = $this->db;
		if ($count > 0) {
			$add_traffic->set('count', new Database_Expression('count+1'));
			$add_traffic->where('ip', $ip);
			$add_traffic->update('apitraffic');

			$traffic_info = $this->db->where('ip', $ip);
			$traffic_info = $traffic_info->get('apitraffic');
			$traffic_info = $traffic_info->result(FALSE);
			$traffic_info = $traffic_info->current();

			return $traffic_info['count'];
		} else {
			$add_traffic->set('ip', $ip);
			$add_traffic->set('count', 1);
			$add_traffic->insert('apitraffic');

			return 1;
		}
	}

	/**
	 * Truncates the apitraffic table (reset every 15 min).
	 *
	 * @return null
	 */
	public function truncate()
	{
		$truncate = $this->db->query('TRUNCATE TABLE apitraffic');
	}

	/**
	 * Checks how many rows are logged for an IP.
	 *
	 * @param string $post The IP.
	 *
	 * @return bool
	 */
	public function count_traffic($ip)
	{
		$count = $this->db->from('apitraffic')->where('ip', $ip)->get()->count();
		return $count;
	}
}
