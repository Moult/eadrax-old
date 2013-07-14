<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
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

    public function add_fan()
    {
        $this->repository->add_fan_to_project($this->fan->id, $this->id);
    }

    public function get_id()
    {
        return $this->id;
    }
}
