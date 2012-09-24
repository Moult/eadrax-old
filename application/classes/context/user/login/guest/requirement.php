<?php
/**
 * Eadrax application/classes/context/user/login/guest/requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Define data model prerequisites to play the Guest role
 *
 * @package    Context
 * @subpackage Role
 */
interface Context_User_Login_Guest_Requirement
{
    /** @ignore */
    public function get_username();
    /** @ignore */
    public function set_username($username);
    /** @ignore */
    public function get_password();
    /** @ignore */
    public function set_password($password);
}
