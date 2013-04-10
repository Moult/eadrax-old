<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Project extends Data\Project
{
    private $repository;
    private $auth;

    public function __construct(Data\Project $project, Repository $repository, Tool\Auth $auth)
    {
        $this->id = $project->id;
        $this->repository = $repository;
        $this->auth = $auth;
    }

    public function authorise()
    {
        $logged_in_user = $this->auth->get_user();
        $project_author = $this->repository->get_project_author($this);
        if ($logged_in_user->id !== $project_author->id)
            throw new Exception\Authorisation('You are not allowed to add a hook to this project');
    }

    public function remove_service(Service $service)
    {
        $this->repository->delete_service_hook_from_project($this, $service);
    }
}
