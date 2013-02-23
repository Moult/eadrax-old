<?php
/**
 * Eadrax Usecase/User/Logout.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Logout\Interactor;
use Eadrax\Core\Usecase\Core;
use Eadrax\Core\Entity;

/**
 * Enacts the usecase for user logout.
 *
 * @package Usecase
 */
class Logout extends Core
{
    /**
     * Auth entity. This context does not require a role.
     * @var Entity\Auth
     */
    public $entity_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct($entity_auth)
    {
        $this->entity_auth = $entity_auth;
    }

    /**
     * Fetches the interactor
     *
     * @return Interactor
     */
    public function fetch()
    {
        return new Interactor($this->entity_auth);
    }
}
