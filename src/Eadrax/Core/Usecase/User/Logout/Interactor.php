<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Logout;
use Eadrax\Core\Tool;

class Interactor
{
    private $authenticator;

    public function __construct(Tool\Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function interact()
    {
        $this->authenticator->logout();
    }
}
