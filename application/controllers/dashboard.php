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
		// Load necessary authentication modules.
		$authlite = new Authlite;

		// Only logged in users can access the dashboard.
		if ($authlite->logged_in() == TRUE)
		{
			// Yes, you are logged in.
			echo 'You are logged in. Welcome, '. $authlite->get_user()->username .'.';
		}
		elseif ($authlite->logged_in() == FALSE)
		{
			// If not logged in, go and log in!
			// Load the view.
			$login = new View('login');

			// Display the page.
			$this->template->content = array($login);
		}
	}
}
