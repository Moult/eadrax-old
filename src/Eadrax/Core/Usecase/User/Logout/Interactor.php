<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Logout;

use Eadrax\Core\Tool;

class Interactor
{
    private $auth;

    public function __construct(Tool\Auth $auth)
    {
        $this->auth = $auth;
    }

    public function interact()
    {
        $this->auth->logout();
    }
}
