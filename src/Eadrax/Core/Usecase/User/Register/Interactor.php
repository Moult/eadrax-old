<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Usecase\User;
use Eadrax\Core\Exception;

class Interactor
{
    private $registrant;
    private $user_login;

    public function __construct(Registrant $registrant, User\Login\Interactor $user_login)
    {
        $this->registrant = $registrant;
        $this->user_login = $user_login;
    }

    public function interact()
    {
        $this->registrant->authorise();
        $this->registrant->validate();
        $this->registrant->register();
        $this->user_login->interact();
    }
}
