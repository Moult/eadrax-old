<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Project
{
    private $auth;

    public function __construct(Data\Project $project, Tool\Auth $auth)
    {
        $this->author = $project->author;
        $this->auth = $auth;
    }

    public function authorise()
    {
        if ($this->auth->get_user()->id !== $this->author->id)
            throw new Exception\Authorisation('You are not allowed to delete this project.');
    }
}
