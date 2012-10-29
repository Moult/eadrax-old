<?php
/**
 * Eadrax application/classes/Context/User/Dashboard/User/Requirement.php
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
interface Context_User_Dashboard_User_Requirement
{
    /** @ignore */
    public function get_username();
    /** @ignore */
    public function set_username($username);
}
