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

		$this->template->content = array($introduction_view);
	}

	public function tour()
	{
		// Introduction Page.
		$introduction_view = new View('tour');

		$this->template->content = array($introduction_view);
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

	public function version()
	{
		$version_view = new View('version');
		$this->template->content = array($version_view);
	}

	public function sponsor()
	{
		$sponsor_view = new View('sponsor');
		$this->template->content = array($sponsor_view);
	}

	public function development()
	{
		$development_view = new View('development');
		$this->template->content = array($development_view);
	}

	public function search()
	{
		// Load necessary models.
		$update_model = new Update_Model;

		if ($this->input->post())
		{
			$keywords = $this->input->post('keywords');
			$search = $this->input->post('search');

			$validate = new Validation($this->input->post());
			$validate->pre_filter('trim');
			$validate->add_rules('keywords', 'required', 'length[5, 50]', 'standard_text');

			if ($validate->validate())
			{
				// Everything went great! Let's recreate our form.
				$search_view = new View('search');
				$search_view->form	= arr::overwrite(array(
					'keywords' => '',
					'search' => ''
					), $validate->as_array());

				// ... and search!
				if ($search == 'profiles') {
					$search = 'users';
				} elseif ($search != 'projects' && $search != 'updates') {
					die(); # TODO dying is never good.
				}
				$keywords = explode(' ', $keywords);
				$search_view->results = $update_model->search($keywords, $search);

				// Then generate content.
				$this->template->content = array($search_view);
			}
			else
			{
				// Errors have occured. Fill in the form and set errors.
				$search_view = new View('search');
				$search_view->form	= arr::overwrite(array(
					'keywords' => '',
					'search' => ''
					), $validate->as_array());
				$search_view->errors = $validate->errors('search_errors');

				// Generate the content.
				$this->template->content = array($search_view);
			}
		}
		else
		{
			// Load the neccessary view.
			$search_view = new View('search');

			// If we didn't press submit, we want a blank form.
			$search_view->form = array('keywords'=>'', 'search'=>'profiles');

			// Generate the content.
			$this->template->content = array($search_view);
		}
	}
}
