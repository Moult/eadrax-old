<?php
/**
 * Eadrax application/classes/Model/User.php
 *
 * @package   Model
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * This is a generic system user.
 *
 * @package Model
 */
class Model_User extends Model_Core
{
    /** @ignore */
    public $username;
    /** @ignore */
    public $password;
    /** @ignore */
    public $email;

    /** @ignore */
    public function get_username()
    {
        return $this->username;
    }

    /** @ignore */
    public function set_username($username)
    {
        $this->username = $username;
    }

    /** @ignore */
    public function get_password()
    {
        return $this->password;
    }

    /** @ignore */
    public function set_password($password)
    {
        $this->password = $password;
    }

    /** @ignore */
    public function get_email()
    {
        return $this->email;
    }

    /** @ignore */
    public function set_email($email)
    {
        $this->email = $email;
    }
}
