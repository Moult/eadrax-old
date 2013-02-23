<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Logout\Interactor;
use Eadrax\Core\Tool;

class Logout
{
    /**
     * Auth entity. This context does not require a role.
     * @var Tool\Auth
     */
    public $entity_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Tool\Auth $entity_auth Authentication system
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
