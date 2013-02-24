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
    private $tool_auth;

    public function __construct($tool_auth)
    {
        $this->tool_auth = $tool_auth;
    }

    public function fetch()
    {
        return new Interactor($this->tool_auth);
    }
}
