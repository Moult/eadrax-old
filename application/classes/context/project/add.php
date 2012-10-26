<?php
/**
 * Eadrax application/classes/context/project/add.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Enacts the usecase for adding a new project.
 *
 * @package Context
 */
class Context_Project_Add extends Context_Core
{
    /**
     * User role
     * @var Context_Project_Add_User
     */
    public $user;

    /**
     * Proposal role
     * @var Context_Project_Add_Proposal
     */
    public $proposal;

    /**
     * Casts data models into roles, and makes each role aware of necessary 
     * dependencies.
     *
     * @param Model_User    $model_user    User data object
     * @param Model_Project $model_project Project data object
     * @param Module_Auth   $module_auth   Authentication system
     * @return void
     */
    public function __construct($model_user, $model_project, $module_auth)
    {
        $this->user = new Context_Project_Add_User($model_user);
        $this->proposal = new Context_Project_Add_Proposal($model_project);
        $repository = new Context_Project_Add_Repository;

        $this->user->link(array(
            'proposal' => $this->proposal,
            'module_auth' => $module_auth
        ));

        $this->proposal->link(array(
            'repository' => $repository
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
            $this->user->authorise_project_add();
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
