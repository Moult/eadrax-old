<?php
/**
 * Eadrax Context/User/Login.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User;
use Eadrax\Eadrax\Context\Core;
use Eadrax\Eadrax\Context\User\Login\Guest;
use Eadrax\Eadrax\Context\User\Login\Repository;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Entity;
use Eadrax\Eadrax\Exception;

/**
 * Enacts the usecase for user login.
 *
 * @package Context
 */
class Login extends Core
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
     * @param Model\User  $model_user  User data object
     * @param Guest       $role_guest  The guest role
     * @param Repository  $repository  The repository
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct(Model\User $model_user, Guest $role_guest, Repository $repository, Entity\Auth $entity_auth, Entity\Validation $entity_validation)
    {
        $role_guest->assign_data($model_user);
        $role_guest->link(array(
            'repository' => $repository,
            'entity_auth' => $entity_auth,
            'entity_validation' => $entity_validation
        ));
        $this->guest = $role_guest;
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
            $this->guest->authorise_login();
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'authorisation',
                'data'   => array(
                    'errors' => array($e->getMessage())
                )
            );
        }
        catch (Exception\Validation $e)
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