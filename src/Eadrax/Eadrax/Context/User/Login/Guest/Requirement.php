<?php
/**
 * Eadrax Context/User/Login/Guest/Requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Login\Guest;

/**
 * Define data prerequisites to play the Guest role
 *
 * @package    Context
 * @subpackage Role
 */
interface Requirement
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
