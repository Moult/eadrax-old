<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Untrack;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    private $authenticator;

    public function __construct(Tool\Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in to untrack a user');
    }
}
