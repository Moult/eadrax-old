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
}
