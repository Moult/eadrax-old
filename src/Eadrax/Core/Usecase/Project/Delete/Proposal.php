<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

class Proposal extends Data\Project
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
            throw new Exception\Authorisation('You cannot delete a project you do not own.');
    }

    public function delete()
    {
        $this->repository->delete($this->id);
    }
}
