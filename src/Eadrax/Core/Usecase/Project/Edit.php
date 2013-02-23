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
    /**
     * Project data
     * @var Data\Project
     */
    private $data_project;

    /**
     * Auth tool
     * @var Tool\Auth
     */
    private $tool_auth;

    /**
     * Validation tool
     * @var Tool\Validation
     */
    private $tool_validation;

    /**
     * Sets up dependencies
     *
     * @param Data\Project               $data_project               The project you want to edit.
     * @param Repository                 $repository                 Repository of current context
     * @param Project\Prepare\Repository $repository_project_prepare Repository of project prepare
     * @param Tool\Auth                $tool_auth                Authentication tool
     * @param Tool\Image               $tool_image               Image tool
     * @param Tool\Validation          $tool_validation          Validation tool
     * @return void
     */
    function __construct(Data\Project $data_project, Repository $repository, Project\Prepare\Repository $repository_project_prepare, Tool\Auth $tool_auth, Tool\Image $tool_image, Tool\Validation $tool_validation)
    {
        $this->data_project = $data_project;
        $this->repository = $repository;
        $this->repository_project_prepare = $repository_project_prepare;
        $this->tool_auth = $tool_auth;
        $this->tool_image = $tool_image;
        $this->tool_validation = $tool_validation;
    }

    /**
     * Fetches the context interactor object
     *
     * @return Interactor
     */
    public function fetch()
    {
        return new Interactor($this->get_user(), $this->get_proposal(), $this->get_project_prepare());
    }

    /**
     * Gets a user role
     *
     * @return User
     */
    private function get_user()
    {
        return new User($this->data_project->get_author(), $this->tool_auth);
    }

    /**
     * Gets a proposal role
     *
     * @return Proposal
     */
    private function get_proposal()
    {
        return new Proposal($this->data_project, $this->repository);
    }

    /**
     * Gets the project prepare interactor
     *
     * @return Project\Prepare\Interactor
     */
    private function get_project_prepare()
    {
        return new Project\Prepare\Interactor($this->get_project_prepare_proposal(), $this->get_project_prepare_icon());
    }

    /**
     * Gets a project prepare proposal role
     *
     * @return Project\Prepare\Proposal
     */
    private function get_project_prepare_proposal()
    {
        return new Project\Prepare\Proposal($this->data_project, $this->tool_validation);
    }

    /**
     * Gets a project prepare icon role
     *
     * @return Project\Prepare\Icon
     */
    private function get_project_prepare_icon()
    {
        return new Project\Prepare\Icon($this->data_project->get_icon(), $this->get_project_prepare_repository(), $this->tool_image, $this->tool_validation);
    }

    /**
     * Gets a project prepare repository
     *
     * @return Project\Prepare\Repository
     */
    private function get_project_prepare_repository()
    {
        return $this->repository_project_prepare;
    }
}
