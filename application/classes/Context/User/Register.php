<?php
/**
 * Eadrax application/classes/Context/User/Register.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Enacts the usecase for user registration.
 *
 * @package Context
 */
class Context_User_Register extends Context_Core
{
    /**
     * Guest role
     * @var Guest
     */
    public $guest;

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
        $this->guest = new Context_User_Register_Guest($model_user);
        $repository = new Context_User_Register_Repository;
        $this->guest->link(array(
            'repository' => $repository,
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
            $this->guest->authorise_registration();
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
        catch (Exception_Validation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'validation',
                'data'   => array(
                    'errors' => $e->as_array()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
