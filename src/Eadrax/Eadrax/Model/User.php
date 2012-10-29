<?php
/**
 * Eadrax Model/User.php
 *
 * @package   Model
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Model;

/**
 * This is a generic system user.
 *
 * @package Model
 */
class User extends Core
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
