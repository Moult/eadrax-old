<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;
use Eadrax\Core\Data;

class Project extends Data\Project
{
    private $repository;

    public function __construct(Data\Project $project, Repository $repository)
    {
        $this->id = $project->id;
        $this->repository = $repository;
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
