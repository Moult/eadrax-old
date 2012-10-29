<?php
/**
 * Eadrax application/classes/Context/User/Login/Guest/Interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Defines what the guest role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Context_User_Login_Guest_Interaction
{
    /**
     * Prove that it is allowed to login an account.
     *
     * @throws Exception_Authorisation if already logged in
     * @return void
     */
    public function authorise_login()
    {
        if ($this->module_auth->logged_in())
            throw new Exception_Authorisation('Logged in users don\'t need to login again.');
        else
            return $this->validate_information();
    }

    /**
     * Makes sure our signup details are valid.
     *
     * @throws Exception_Validation
     * @return void
     */
    public function validate_information()
    {
        $validation = Validation::factory(get_object_vars($this))
            ->rule('username', 'not_empty')
            ->rule('username', array($this, 'is_existing_account'), array(':validation', 'username', 'password'));

        if ($validation->check())
            return $this->login();
        else
            throw new Exception_Validation($validation->errors('context/user/login/errors'));
    }

    /**
     * Checks whether or not a username is unique.
     *
     * @param array  $array        An array of username and password data.
     * @param string $username_key The key in $array where the username is.
     * @param string $password_key The key in $array where the password is.
     * @return bool
     */
    public function is_existing_account($array, $username_key, $password_key)
    {
        return $this->repository->is_existing_account($array[$username_key], $array[$password_key]);
    }

    /**
     * Logs the guest into the system.
     *
     * @return void
     */
    public function login()
    {
        return $this->module_auth->login($this->username, $this->password);
    }
}
