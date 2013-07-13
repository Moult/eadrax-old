<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Project extends Data\Project
{
    public $id;
    public $name;
    public $author;
    private $repository;
    private $authenticator;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator)
    {
        list($project_name, $author_id, $author_username) = $repository->get_project_name_and_author_id_and_username($project->id);

        $this->id = $project->id;
        $this->name = $project_name;
        $this->author = new Data\User;
        $this->author->id = $author_id;
        $this->author->username = $author_username;

        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise_ownership()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->author->id)
            throw new Exception\Authorisation('You are not allowed to add an update to this project');
    }
}
