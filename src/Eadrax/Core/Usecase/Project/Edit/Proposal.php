<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

class Proposal extends Data\Project
{
    public $id;
    public $name;
    public $summary;
    public $description;
    public $website;
    public $last_updated;
    private $repository;
    private $authenticator;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $project->id;
        $this->name = $project->name;
        $this->summary = $project->summary;
        $this->description = $project->description;
        $this->website = $project->website;
        $this->last_updated = time();

        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->repository->get_project_author_id($this->id))
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }

    public function update()
    {
        $this->repository->update(
            $this->id,
            $this->name,
            $this->summary,
            $this->description,
            $this->website,
            $this->last_updated
        );
    }
}
