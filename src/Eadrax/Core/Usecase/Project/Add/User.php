<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Usecase;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class User extends Data\User
{
    private $tool_auth;

    public function __construct(Data\User $data_user, Tool\Auth $tool_auth)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->tool_auth = $tool_auth;
    }

    public function authorise_project_add()
    {
        if ($this->tool_auth->logged_in())
            return TRUE;
        else
            throw new Exception\Authorisation('Please login before you can add a new project.');
    }
}
