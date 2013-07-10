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
    public $id;
    private $repository;
    private $authenticator;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $project->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->repository->get_project_author_id($this->id))
            throw new Exception\Authorisation('You are not allowed to add a hook to this project');
    }

    public function has_service(Service $service)
    {
        return $this->repository->has_existing_service($this->id, $service->url);
    }

    public function add_service(Service $service)
    {
        $this->repository->add_service_hook($this->id, $service->url);
    }
}
