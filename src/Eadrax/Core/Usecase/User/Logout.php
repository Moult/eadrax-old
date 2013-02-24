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
    private $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    public function fetch()
    {
        return new Interactor($this->auth);
    }
}
