<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

class Author extends Data\User
{
    private $authenticator;

    public function __construct(Tool\Authenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in to edit a project.');
    }
}
