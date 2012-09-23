<?php
/**
 * Eadrax application/classes/model/user.php
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

    /**
     * Allows you to set properties whilst instantiating the object.
     *
     * $model_user = new Model_User(array('username' => 'foobar'));
     *
     * @param array $properties The list of properties to preset
     * @return void
     */
    public function __construct(Array $properties = array())
    {
        foreach ($properties as $property_name => $property_value) {
            $this->{'set_'.$property_name}($property_value);
        }
    }

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
