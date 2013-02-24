<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;

use Eadrax\Core\Usecase\Project\Add\User;
use Eadrax\Core\Usecase\Project\Add\Interactor;
use Eadrax\Core\Usecase\Project\Add\Proposal;
use Eadrax\Core\Usecase\Project\Add\Icon;
use Eadrax\Core\Usecase\Project\Add\Repository;
use Eadrax\Core\Usecase;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Add
{
    private $data_project;
    private $data_user;
    private $data_file;
    private $repository;
    private $repository_project_prepare;
    private $tool_auth;
    private $tool_image;
    private $tool_validation;

    public function __construct(Data\Project $data_project, Repository $repository, Usecase\Project\Prepare\Repository $repository_project_prepare, Tool\Auth $tool_auth, Tool\Validation $tool_validation, Tool\Image $tool_image)
    {
        $this->data_project = $data_project;
        $this->data_user = $data_project->get_author();
        $this->data_file = $data_project->get_icon();
        $this->repository = $repository;
        $this->repository_project_prepare = $repository_project_prepare;
        $this->tool_auth = $tool_auth;
        $this->tool_image = $tool_image;
        $this->tool_validation = $tool_validation;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_proposal(),
            $this->get_user(),
            $this->get_project_prepare()
        );
    }

    private function get_proposal()
    {
        return new Proposal($this->data_project, $this->repository);
    }

    private function get_user()
    {
        return new User($this->data_user, $this->tool_auth);
    }

    private function get_project_prepare()
    {
        return new Usecase\Project\Prepare\Interactor(
            $this->get_project_prepare_proposal(),
            $this->get_project_prepare_icon()
        );
    }

    private function get_project_prepare_proposal()
    {
        return new Usecase\Project\Prepare\Proposal($this->data_project, $this->tool_validation);
    }

    private function get_project_prepare_icon()
    {
        return new Usecase\Project\Prepare\Icon($this->data_file, $this->repository_project_prepare, $this->tool_image, $this->tool_validation);
    }
}
