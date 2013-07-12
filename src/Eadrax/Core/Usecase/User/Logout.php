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
    private $authenticator;

    public function __construct(Tool\Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function fetch()
    {
        return new Interactor($this->authenticator);
    }
}
