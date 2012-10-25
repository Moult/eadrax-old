<?php
/**
 * Eadrax application/classes/context/project/add/user/requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Define data model prerequisites to play the user role
 *
 * @package    Context
 * @subpackage Role
 */
interface Context_Project_Add_User_Requirement
{
    /** @ignore */
    public function get_id();
    /** @ignore */
    public function set_id($id);
}
