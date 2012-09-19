<?php
/**
 * Eadrax application/classes/context/user/register/factory.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Dependency injection to load all related data models, repositories, and 
 * vendor modules to prepare the Context for execution.
 *
 * @package Context
 */
class Context_User_Register_Factory
{
    /**
     * Model_User_Array
     * @var array
     */
    private $model_user_array;

    /**
     * Converts raw data into models
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->model_user_array = array(
            'username' => $data['username'],
            'password' => $data['password'],
            'email'    => $data['email']
        );
    }

    /**
     * Loads the context
     *
     * @return Context_User_Register
     */
    public function fetch()
    {
        return new Context_User_Register(
            $this->model_user(),
            $this->module_auth()
        );
    }

    /**
     * Data object for users
     *
     * @return Model_User
     */
    public function model_user()
    {
        return new Model_User($this->model_user_array);
    }

    /**
     * This is a Kohana module.
     *
     * @return Auth
     */
    public function module_auth()
    {
        return Auth::instance();
    }
}
