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

/**
 *
 * Updates controller added for update system.
 *
 * @category	Eadrax
 * @package		Update
 * @subpackage	Controllers
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 * @version		$Id$
 */
class Updates_Controller extends Core_Controller {
	/**
	 * Adds a new update.
	 *
	 * @return null
	 */
	public function index()
	{
		// Logged in users and guest users will have different abilities when 
		// submitting updates to the website.
		if ($this->logged_in == TRUE)
		{
			// If the person is logged in...make sure they really are.
			$this->restrict_access();

			// Load necessary models.
			$update_model = new Update_Model;

			if ($this->input->post())
			{
				$summary	= $this->input->post('summary');
				$detail		= $this->input->post('detail');
				$pid		= 1; # TODO

				// Begin to validate the information.
				$validate = new Validation($this->input->post());
				$validate->pre_filter('trim');
				$validate->add_rules('summary', 'required', 'length[5, 70]', 'standard_text');
				$validate->add_rules('detail', 'standard_text');

				if ($validate->validate())
				{
					// Everything went great! Let's add the update.
					$update_model->manage_update(array(
						'uid' => $this->uid,
						'summary' => $summary,
						'detail' => $detail,
						'pid' => $pid
					));

					// Then load our success view.
					$update_success_view = new View('update_success');

					// Then generate content.
					$this->template->content = array($update_success_view);
				}
				else
				{
					// Errors have occured. Fill in the form and set errors.
					$update_form_view = new View('update_form');
					$update_form_view->form = arr::overwrite(array(
						'summary' => '',
						'detail' => ''
					), $validate->as_array());
					$update_form_view->errors = $validate->errors('update_errors');

					// Generate the content.
					$this->template->content = array($update_form_view);
				}
				// TODO
			}
			else
			{
				// Load the necessary view.
				$update_form_view = new View('update_form');

				// If we didn't press submit, we want a blank form.
				$update_form_view->form = array(
					'summary' => '',
					'detail' => ''
				);

				// Generate the content.
				$this->template->content = array($update_form_view);
			}
		}
		else
		{
			// The person is a guest...
			// TODO
		}
	}
}
