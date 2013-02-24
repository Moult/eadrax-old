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
    private $tool_auth;

    public function __construct(Data\User $data_user, Tool\Auth $tool_auth)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->tool_auth = $tool_auth;
    }

    public function authorise_project_edit()
    {
        if ($this->tool_auth->logged_in())
            return TRUE;
        else
            throw new Exception\Authorisation('You need to be logged in to edit a project.');
    }

    public function check_proposal_author()
    {
        if ($this->id === $this->tool_auth->get_user()->id)
            return TRUE;
        else
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }
}
