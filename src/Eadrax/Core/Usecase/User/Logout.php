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
     * Auth tool. This context does not require a role.
     * @var Tool\Auth
     */
    public $tool_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Tool\Auth $tool_auth Authentication system
     * @return void
     */
    public function __construct($tool_auth)
    {
        $this->tool_auth = $tool_auth;
    }

    /**
     * Fetches the interactor
     *
     * @return Interactor
     */
    public function fetch()
    {
        return new Interactor($this->tool_auth);
    }
}
