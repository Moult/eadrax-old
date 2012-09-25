<?php
/**
 * Eadrax application/classes/context/user/logout/factory.php
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
class Context_User_Logout_Factory extends Context_Factory
{
    /**
     * Loads the context
     *
     * @return Context_User_Logout
     */
    public function fetch()
    {
        return new Context_User_Logout(
            $this->module_auth()
        );
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
