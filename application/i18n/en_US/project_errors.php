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
 * @package		Project
 * @author		Eadrax Team
 * @copyright	Copyright (C) 2009 Eadrax Team
 */

$lang = array(
	'name' => array(
		'required'		=> 'The project name cannot be blank.',
		'length'		=> 'Project name must be between 1-25 characters.',
		'standard_text'	=> 'There are illegal characters in the project name.'
	),

	'website' => array(
		'url' => 'This is not a valid website URL.'
	),

	'contributors' => array(
		'standard_text' => 'There are illegal characters in the contributors list.'
	),

	'description' => array(
		'required' => 'The description cannot be blank.'
	),

	'cid' => array(
		'required' => 'Please choose a category.',
		'between' => 'The category you have chosen does not exist.'
	)
);
