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
 * @package		Site
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

/**
 *
 * Static main website pages, including the homepage.
 *
 * @category	Eadrax
 * @package		Site
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Site_Controller extends Core_Controller {
	/**
	 * This is the index page of the site.
	 *
	 * @return null
	 */
	public function index()
	{
		// Introduction Page.
		$introduction_view = new View('introduction');

		// Let's get our login form!
		$changelog_view = new View('changelog');

		$this->template->content = array($introduction_view, $changelog_view);
	}

	/**
	 * Page that shows legal information.
	 *
	 * @return null
	 */
	public function legal()
	{
		$legal_view = new View('legal');
		$this->template->content = array($legal_view);
	}
}
