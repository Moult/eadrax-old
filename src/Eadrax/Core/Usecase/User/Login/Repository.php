<?php
/**
 * Eadrax Usecase/User/Login/Repository.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User\Login;

/**
 * Handles persistance during user registration.
 *
 * @package    Usecase
 * @subpackage Repository
 */
interface Repository
{
    /**
     * Checks whether or not an account exists.
     *
     * @param string $username The username of the account
     * @param string $password The password of the account
     * @return bool
     */
    public function is_existing_account($username, $password);
}