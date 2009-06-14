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
	public $template	= 'template';
	public $site_name	= 'Eadrax';
	public $slogan		= 'Totally awesome.';

	/**
	 * Redirects users to the login form if they are not signed in.
	 *
	 * @param bool $reverse If TRUE, does the opposite.
	 *
	 * @return null
	 */
	public function restrict_access($reverse = FALSE)
	{
		// Load necessary authentication modules.
		$authlite = new Authlite;

		if ($reverse == FALSE)
		{
			if ($authlite->logged_in() == FALSE)
			{
				// Not elegant, rewrite later.
				die ('You cannot access this page. Please log in.';
			}
		}
		elseif ($reverse == TRUE)
		{
			if ($authlite->logged_in() == TRUE)
			{
				// Not elegant, rewrite later.
				// Useful for login/register pages.
				die ('Only guests can access this page.';
			}
		}
	}
}
