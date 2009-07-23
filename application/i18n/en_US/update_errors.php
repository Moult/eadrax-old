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

$lang = array(
	'summary' => array(
		'required'		=> 'The update summary cannot be blank.',
		'length'		=> 'Update summary must be between 5-70 characters.',
		'standard_text'	=> 'There are illegal characters in the update summary.'
	),

	'detail' => array(
		'standard_text' => 'There are illegal characters in the detailed description.'
	),

	'pid' => array(
		'required' => 'Please provide a project for this update to be part of.',
		'project_owner' => 'You can only add updates to projects you have created.',
		'digit' => 'Your project seems to be invalid.'
	),

	'syntax' => array(
		'syntax_language' => 'Syntax highlighting for that language is not supported'
	),

	'captcha' => array(
		'captcha' => 'You have entered the CAPTCHA code wrong.'
	)
);
