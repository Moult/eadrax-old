<?php
/**
 * Eadrax application/classes/Context/User/Dashboard.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Enacts the usecase for user dashboard.
 *
 * @package Context
 */
class Context_User_Dashboard extends Context_Core
{
    /**
     * User role
     * @var User
     */
    public $user;

    /**
     * Casts data models into roles, and makes each role aware of necessary 
     * dependencies.
     *
     * @param Model_User  $model_user  User data object
     * @param Module_Auth $module_auth Authentication system
     * @return void
     */
    public function __construct($model_user, $module_auth)
    {
        $this->user = new Context_User_Dashboard_User($model_user);
        $this->user->link(array(
            'module_auth' => $module_auth
        ));
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
     */
    public function execute()
    {
        try
        {
            $data = $this->user->authorise_dashboard();
        }
        catch (Exception_Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'authorisation',
                'data'   => array(
                    'errors' => array($e->getMessage())
                )
            );
        }

        return array(
            'status' => 'success',
            'data'   => $data
        );
    }
}
