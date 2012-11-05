<?php
/**
 * Eadrax Context/User/Dashboard.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User;
use Eadrax\Eadrax\Context\Core;
use Eadrax\Eadrax\Context\User\Dashboard\User;
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Exception;
use Eadrax\Eadrax\Entity;

/**
 * Enacts the usecase for user dashboard.
 *
 * @package Context
 */
class Dashboard extends Core
{
    /**
     * User role
     * @var User
     */
    public $user;

    /**
     * Casts data into roles, and makes each role aware of necessary 
     * dependencies.
     *
     * @param Data\User  $data_user  User data object
     * @param User        $role_user   The user role
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct(Data\User $data_user, User $role_user, Entity\Auth $entity_auth)
    {
        $role_user->assign_data($data_user);
        $role_user->link(array(
            'entity_auth' => $entity_auth
        ));
        $this->user = $role_user;
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

        return array(
            'status' => 'success',
            'data'   => $data
        );
    }
}
