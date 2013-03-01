<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;

use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

class User extends Data\User
{
    private $auth;

    public function __construct(Data\User $user, Tool\Auth $auth)
    {
        foreach ($user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->auth = $auth;
    }

    public function authorise()
    {
        if ($this->auth->logged_in())
            return TRUE;
        else
            throw new Exception\Authorisation('You need to be logged in to edit a project.');
    }

    public function verify_ownership()
    {
        if ($this->id === $this->auth->get_user()->id)
            return TRUE;
        else
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }
}
