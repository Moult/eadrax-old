<?php
/**
 * Eadrax application/classes/Context/Project/Add/Factory.php
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
class Context_Project_Add_Factory extends Context_Factory
{
    /**
     * Loads the context
     *
     * @return Context_Project_Add
     */
    public function fetch()
    {
        return new Context_Project_Add(
            $this->model_user(),
            $this->model_project(),
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
        return new Model_User;
    }

    /**
     * Data object for projects
     *
     * @return Model_Project
     */
    public function model_project()
    {
        return new Model_Project(array(
            'name' => $this->get_data('name'),
            'summary' => $this->get_data('summary')
        ));
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
