<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Proposal
{
    public $id;
    public $project;
    private $repository;
    private $authenticator;

    public function __construct(Data\Update $update, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $update->id;
        $this->project = new Data\Project;
        $this->project->author = new Data\User;
        $this->project->author->id = $repository->get_author_id($this->id);
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->project->author->id)
            throw new Exception\Authorisation('You are not allowed to delete this update');
    }

    public function delete()
    {
        $this->repository->purge_files($this->id);
        $this->repository->delete_update($this->id);
    }
}
