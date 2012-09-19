<?php
/**
 * Eadrax application/classes/context/user/register/guest/interaction.php
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
trait Context_User_Register_Guest_Interaction
{
    /**
     * Prove that it is allowed to register an account.
     *
     * @throws Exception_Authorisation if already logged in
     *
     * @return void
     */
    public function authorise_registration()
    {
        if ($this->module_auth->logged_in())
        {
            throw new Exception_Authorisation('Logged in users cannot register new accounts.');
        }
        else
        {
            $this->validate_information();
        }
    }

    /**
     * Makes sure our signup details are valid.
     *
     * @throws Exception_Validation
     *
     * @return void
     */
    public function validate_information()
    {
        $validation = Validation::factory(get_object_vars($this))
            ->rule('username', 'not_empty')
            ->rule('username', 'regex', array(':value', '/^[a-z_.]++$/iD'))
            ->rule('password', 'not_empty')
            ->rule('password', 'min_length', array(':value', '6'))
            ->rule('email', 'not_empty')
            ->rule('email', 'email');
        if ($validation->check())
        {
            $this->register();
        }
        else
        {
            throw new Exception_Validation($validation->errors('context/user/register/errors'));
        }
    }

    /**
     * Registers the guest as a new user.
     *
     * @return void
     */
    public function register()
    {
        $this->repository->register($this);
    }
}
