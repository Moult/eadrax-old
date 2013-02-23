<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Data;

class User
{
    /** @ignore */
    public $username;
    /** @ignore */
    public $password;
    /** @ignore */
    public $email;
    /** @ignore */
    public $id;

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

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }
}
