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

namespace Eadrax\Core\Context\User;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Context\User\Dashboard\Interactor;
use Eadrax\Core\Context\User\Dashboard\User;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Entity;

/**
 * Enacts the usecase for user dashboard.
 *
 * @package Context
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
     * @var Entity\Auth
     */
    private $entity_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\User   $data_user  User data object
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct(Data\User $data_user, Entity\Auth $entity_auth)
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
