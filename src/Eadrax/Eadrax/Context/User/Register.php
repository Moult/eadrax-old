<?php
/**
 * Eadrax Context/User/Register.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User;
use Eadrax\Eadrax\Context\Core;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Exception;
use Eadrax\Eadrax\Entity;

/**
 * Enacts the usecase for user registration.
 *
 * @package Context
 */
class Register extends Core
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
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct($model_user, $role_guest, $repository, $entity_auth)
    {
        $role_guest->assign_data($model_user);
        $role_guest->link(array(
            'repository' => $repository,
            'entity_auth' => $entity_auth
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
            $this->guest->authorise_registration();
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
