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
 * For overriding to give custom 404 error pages.
 *
 * @category	Eadrax
 * @package		Core
 * @subpackage	Hooks
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class my_404 extends Core_Controller {

	public function __construct()
	{
		Event::clear('system.404', array('Kohana', 'show_404'));
		Event::add('system.404', array($this, 'show_404'));
	}

	/**
	 * Page that shows the 404 error.
	 *
	 * @return null
	 */
	public function show_404()
	{
		header('HTTP/1.1 404 File Not Found');
		Kohana::$instance = new Site_Controller();
		$template = new View('template/template');
		$error404_view = new View('error404');
		$template->content = array($error404_view);
		$template->latest_data = Site_Controller::_get_latest_data();
		$template->render(TRUE);
		die();
	}
}

new my_404;
