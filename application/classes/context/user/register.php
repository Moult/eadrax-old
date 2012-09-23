<?php
/**
 * Eadrax application/classes/context/user/register.php
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
     *
     * @return void
     */
    public function __construct($model_user, $module_auth)
    {
        $this->guest = new Guest($model_user);
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

/**
 * Define data model prerequisites to play the Guest role
 *
 * @package    Context
 * @subpackage Role
 */
interface Guest_Requirements
{
    /** @ignore */
    public function get_username();
    /** @ignore */
    public function set_username($username);
    /** @ignore */
    public function get_password();
    /** @ignore */
    public function set_password($password);
    /** @ignore */
    public function get_email();
    /** @ignore */
    public function set_email($email);
}

/**
 * Allows model_user to be cast as a guest role
 *
 * @package    Context
 * @subpackage Role
 */ 
abstract class Cast_Guest extends Model_User implements Guest_Requirements
{
    use Context_Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model_User $model_user Data object to copy
     */
    public function __construct(Model_User $model_user)
    {
        parent::__construct(get_object_vars($model_user));
    }
}

/**
 * Defines the guest role and injects its interactions
 *
 * @package    Context
 * @subpackage Role
 */
class Guest extends Cast_Guest
{
    use Context_User_Register_Guest_Interaction;
}
