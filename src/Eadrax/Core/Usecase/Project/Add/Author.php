<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Author extends Data\User
{
    private $auth;

    public function __construct(Tool\Auth $auth)
    {
        $this->auth = $auth;
    }

    public function authorise()
    {
        if ( ! $this->auth->logged_in())
            throw new Exception\Authorisation('Please login before you can add a new project.');
    }
}
