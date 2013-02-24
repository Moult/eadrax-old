<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;

use Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Edit\User;
use Eadrax\Core\Usecase\Project\Edit\Interactor;
use Eadrax\Core\Usecase\Project\Edit\Proposal;
use Eadrax\Core\Usecase\Project\Edit\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Edit
{
    private $data_project;
    private $repository;
    private $repository_project_prepare;
    private $tool_auth;
    private $tool_image;
    private $tool_validation;

    function __construct(Data\Project $data_project, Repository $repository, Project\Prepare\Repository $repository_project_prepare, Tool\Auth $tool_auth, Tool\Image $tool_image, Tool\Validation $tool_validation)
    {
        $this->data_project = $data_project;
        $this->repository = $repository;
        $this->repository_project_prepare = $repository_project_prepare;
        $this->tool_auth = $tool_auth;
        $this->tool_image = $tool_image;
        $this->tool_validation = $tool_validation;
    }

    public function fetch()
    {
        return new Interactor($this->get_user(), $this->get_proposal(), $this->get_project_prepare());
    }

    private function get_user()
    {
        return new User($this->data_project->get_author(), $this->tool_auth);
    }

    private function get_proposal()
    {
        return new Proposal($this->data_project, $this->repository);
    }

    private function get_project_prepare()
    {
        return new Project\Prepare\Interactor($this->get_project_prepare_proposal(), $this->get_project_prepare_icon());
    }

    private function get_project_prepare_proposal()
    {
        return new Project\Prepare\Proposal($this->data_project, $this->tool_validation);
    }

    private function get_project_prepare_icon()
    {
        return new Project\Prepare\Icon($this->data_project->get_icon(), $this->get_project_prepare_repository(), $this->tool_image, $this->tool_validation);
    }

    private function get_project_prepare_repository()
    {
        return $this->repository_project_prepare;
    }
}
