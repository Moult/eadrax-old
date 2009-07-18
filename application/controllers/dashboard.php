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
 * @package		Dashboard
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Access the dashboard and dashboard related items.
 *
 * @category	Eadrax
 * @package		Dashboard
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Dashboard_Controller extends Core_Controller {
	/**
	 * This is the main dashboard for all users.
	 *
	 * @return null
	 */
	public function index()
	{
		// Only logged in users are allowed.
		$this->restrict_access();

		// Load necessary models.
		$user_model = new User_Model;
		$track_model = new Track_Model;

		// Load the view.
		$dashboard_view = new View('dashboard');

		// Send some basic variables to the view.
		$dashboard_view->username = $this->username;

		// Create the "tracking" widget.
		$dashboard_tracking_view = new View('dashboard_tracking');
		$dashboard_tracking_view->total = $track_model->track($this->uid);
		$track_uids = $track_model->track_list($this->uid);
		$track_list = array();

		// Track uids only contains uids, however usernames are useful for the 
		// view too!
		foreach ($track_uids as $uid)
		{
			$username = $user_model->user_information($uid);
			$username = $username['username'];
			$track_list[] = array($uid, $username);
		}

		$dashboard_tracking_view->track_list = $track_list;

		// Generate the content.
		$this->template->content = array($dashboard_view, $dashboard_tracking_view);
	}
}
