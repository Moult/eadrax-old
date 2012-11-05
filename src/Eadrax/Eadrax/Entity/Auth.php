<?php
/**
 * Eadrax Entity/Auth.php
 *
 * @package   Entity
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Entity;

/**
 * Allows for user authentication
 *
 * @package Entity
 */
interface Auth
{
    /**
     * Checks whether or not the user is currently logged in.
     *
     * Example:
     * $auth = new Auth_Class;
     * if ($auth->logged_in() === TRUE) {} // Use is logged in
     *
     * @return bool
     */
    public function logged_in();

    /**
     * Retrieves the currently logged in user in the form of a Model\User.
     *
     * Example:
     * $auth->get_user()->get_username();
     *
     * @return Eadrax\Eadrax\Model\User
     */
    public function get_user();

    /**
     * Logs in the user with the details provided
     *
     * Example:
     * $auth->login('username', 'password');
     * var_dump($auth->logged_in()); // bool(true)
     *
     * @param string username The username of the user
     * @param string password The password of the user
     * @return bool Whether or not the login operation succeeded
     */
    public function login($username, $password);

    /**
     * Logs out the currently logged in user.
     *
     * Example:
     * $auth->logout();
     *
     * @return void
     */
    public function logout();
}
