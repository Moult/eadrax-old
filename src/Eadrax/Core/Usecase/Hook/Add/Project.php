<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;
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

    public function has_service(Service $service)
    {
        return $this->repository->project_has_service($this, $service);
    }

    public function add_service(Service $service)
    {
        $this->repository->add_service_hook_to_project($this, $service);
    }
}
