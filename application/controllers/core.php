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
 * @package		Core
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Core controller that everything uses.
 *
 * Sets some globally required methods and variables.
 *
 * @category	Eadrax
 * @package		Core
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
abstract class Core_Controller extends Template_Controller {
	// We do not yet have a template.
	public $template	= 'template/template';

	// Useful authentication variables.
	public $logged_in;
	public $username;
	public $uid;

	/**
	 * Sets up some useful variables.
	 *
	 * @return null
	 */
	public function __construct()
	{
		parent::__construct();

		// Set the useful authentication variables.
		$this->authlite = new Authlite;
		if ($this->authlite->logged_in() == TRUE)
		{
			$this->username		= $this->authlite->get_user()->username;
			$this->uid			= $this->authlite->get_user()->id;
			$this->logged_in	= TRUE;

			$log_user = new User_Model;
			$log_user->log_user($this->uid);
		}
		elseif ($this->authlite->logged_in() == FALSE)
		{
			$this->username		= 'Guest';
			$this->uid			= 1;
			$this->logged_in	= FALSE;
		}

		// Send all the data we collected to the template...
		$this->template->latest_data = $this->_get_latest_data();
		
		// Loading Libraries
		$this->session = Session::instance();
		
		$this->head = Head::instance();

		// Javascripts
		$this->head->javascript->append_file('js/lib/jquery-1.3.2.min.js');
		$this->head->javascript->append_file('js/lib/jquery-ui-1.7.2.custom.min.js');
		$this->head->javascript->append_file('js/base.js');

		// Stylesheets
		$this->head->css->append_file('css/ui-darkness/jquery-ui-1.7.2.custom');

		$this->template->set_global('head', $this->head);
	}

	/**
	 * Redirects users to the login form if they are not signed in.
	 *
	 * @param bool $reverse If TRUE, does the opposite.
	 *
	 * @return null
	 */
	public function restrict_access($reverse = FALSE)
	{
		if ($reverse == FALSE)
		{
			if ($this->logged_in == FALSE)
			{
				url::redirect('users/login');
			}
		}
		elseif ($reverse == TRUE)
		{
			if ($this->logged_in == TRUE)
			{
				// Useful for login/register pages.
				url::redirect('dashboard');
			}
		}
	}

	/**
	 * Retries the latest submitted updates to show in the template footer.
	 *
	 * @return array
	 */
	public function _get_latest_data()
	{
		$update_model = new Update_Model;
		$project_model = new Project_Model;
		$user_model = new User_Model;

		// Let's find out some latest updates!
		$latest_query = $update_model->updates(NULL, NULL, 'DESC', 3);
		$n = 0;
		$latest_data = array();

		foreach ($latest_query->result() as $row)
		{
			$latest_data[$n]['summary'] = $row->summary;
			$latest_data[$n]['id'] = $row->id;
			$latest_data[$n]['uid'] = $row->uid;

			// Let's parse the thumbnails.
			$latest_data[$n]['filename0'] = Updates_Controller::_file_icon($row->filename0, $row->ext0);

			// Determine the sizes for offsetting.
			$path = substr($latest_data[$n]['filename0'],strlen(url::base()));
			$path = DOCROOT . $path;
			$latest_data[$n]['thumb_height'] = getimagesize($path);
			$latest_data[$n]['thumb_width']  = $latest_data[$n]['thumb_height'][0];
			$latest_data[$n]['thumb_height'] = $latest_data[$n]['thumb_height'][1];
			$latest_data[$n]['thumb_offset'] = $latest_data[$n]['thumb_height']/2;

			// Right, now find out the project name.
			if ($row->pid == 0) {
				$latest_data[$n]['project_name'] = 'Uncategorised';
				$latest_data[$n]['pid'] = 0;
			} else {
				$latest_data[$n]['pid'] = $row->pid;
				$latest_data[$n]['project_name'] = $project_model->project_information($row->pid);
				$latest_data[$n]['project_name'] = $latest_data[$n]['project_name']['name'];
			}

			// Find out the user name.
			$latest_data[$n]['user_information'] = $user_model->user_information($row->uid);

			$n++;
		}
		return $latest_data;
	}
}
