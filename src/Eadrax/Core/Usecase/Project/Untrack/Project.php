<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Project extends Data\Project
{
    public $id;
    private $repository;
    private $authenticator;
    private $fan;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $project->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->fan = $this->authenticator->get_user();
    }

    public function has_fan()
    {
        return $this->repository->does_project_have_fan($this->id, $this->fan->id);
    }

    public function get_project_author_id()
    {
        return $this->repository->get_project_author_id($this->id);
    }

    public function remove_fan()
    {
        $this->repository->remove_fan_from_project($this->fan->id, $this->id);
    }
}
