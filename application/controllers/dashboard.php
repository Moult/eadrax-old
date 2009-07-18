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
		$subscribe_model = new Subscribe_Model;
		$project_model = new Project_Model;

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

		// Create the "subscribed" widget.
		$dashboard_subscribed_view = new View('dashboard_subscribed');
		$subscribed_total = 0;
		$projects = $project_model->projects($this->uid);

		// We will not track the "Uncategorised" project as it is special.
		unset($projects[1]);

		$project_subscribe_list = array();

		foreach ($projects as $pid => $p_name)
		{
			$subscribe_uids = $subscribe_model->subscribe_list($pid);
			$subscribe_list = array();

			foreach ($subscribe_uids as $uid)
			{
				$username = $user_model->user_information($uid);
				$username = $username['username'];
				$subscribe_list[] = array($uid, $username);
			}

			$subscribed_number = $subscribe_model->subscribe($pid);
			$project_subscribe_list[$pid] = array($p_name, $subscribed_number, $subscribe_list);

			// Add up the number of subscribers
			$subscribed_total = $subscribed_total + $subscribed_number;
		}

		// Set all the information we gathered...
		$dashboard_subscribed_view->project_subscribe_list = $project_subscribe_list;
		$dashboard_subscribed_view->total = $subscribed_total;

		// Generate the content.
		$this->template->content = array($dashboard_view, $dashboard_tracking_view, $dashboard_subscribed_view);
	}
}
