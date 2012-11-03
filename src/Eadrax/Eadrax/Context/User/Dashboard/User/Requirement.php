<?php
/**
 * Eadrax Context/User/Dashboard/User/Requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Dashboard\User;

/**
 * Define data model prerequisites to play the user role
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
}
