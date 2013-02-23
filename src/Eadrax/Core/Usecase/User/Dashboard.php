<?php
/**
 * Eadrax Usecase/User/Dashboard.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\Core;
use Eadrax\Core\Usecase\User\Dashboard\Interactor;
use Eadrax\Core\Usecase\User\Dashboard\User;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

/**
 * Enacts the usecase for user dashboard.
 *
 * @package Usecase
 */
class Dashboard extends Core
{
    /**
     * User data
     * @var Data\User
     */
    private $data_user;

    /**
     * Auth entity
     * @var Tool\Auth
     */
    private $entity_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\User   $data_user  User data object
     * @param Tool\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct(Data\User $data_user, Tool\Auth $entity_auth)
    {
        $this->data_user = $data_user;
        $this->entity_auth = $entity_auth;
    }

    /**
     * Fetches the interactor
     *
     * @return void
     */
    public function fetch()
    {
        return new Interactor($this->get_user());
    }

    /**
     * Gets a user role
     *
     * @return User
     */
    private function get_user()
    {
        return new User($this->data_user, $this->entity_auth);
    }
}
