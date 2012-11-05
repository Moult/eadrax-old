<?php
/**
 * Eadrax Context/User/Register/Repository.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Register;

/**
 * Handles persistance during user registration.
 *
 * @package    Context
 * @subpackage Repository
 */
interface Repository
{
    /**
     * Saves a user
     *
     * @param Data_User $data_user The user to save
     * @return void
     */
    public function register($data_user);

    /**
     * Checks whether or not a username is unique
     *
     * @param string $username The username to check
     * @return bool
     */
    public function is_unique_username($username);
}
