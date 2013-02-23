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
use Eadrax\Core\Exception;

class Add
{
    /**
     * Project data
     * @var Data\Project
     */
    private $data_project;

    /**
     * User data
     * @var Data\User
     */
    private $data_user;

    /**
     * File data
     * @var Data\File
     */
    private $data_file;

    /**
     * Project add repository
     * @var Repository
     */
    private $repository;

    /**
     * Repository used by subcontext project prepare
     * @var Usecase\Project\Prepare\Repository
     */
    private $repository_project_prepare;

    /**
     * Auth tool
     * @var Tool\Auth
     */
    private $tool_auth;

    /**
     * Image tool used by subcontext project prepare
     * @var Tool\Image
     */
    private $tool_image;

    /**
     * Validation tool used by subcontext project prepare
     * @var Tool\Validation
     */
    private $tool_validation;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\Project                       $data_project               Project data object
     * @param Usecase\Project\Add\Repository     $repository                 Repository
     * @param Usecase\Project\Prepare\Repository $repository_project_prepare Repository
     * @param Tool\Auth                        $tool_auth                Authentication system
     * @param Tool\Validation                  $tool_validation          Validation system
     * @param Tool\Image                       $tool_image               Image system
     * @return void
     */
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
