<?php
/**
 * Eadrax Usecase/Project/Add.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
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

/**
 * Enacts the usecase for adding a new project.
 *
 * @package Usecase
 */
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
     * Auth entity
     * @var Tool\Auth
     */
    private $entity_auth;

    /**
     * Image entity used by subcontext project prepare
     * @var Tool\Image
     */
    private $entity_image;

    /**
     * Validation entity used by subcontext project prepare
     * @var Tool\Validation
     */
    private $entity_validation;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\Project                       $data_project               Project data object
     * @param Usecase\Project\Add\Repository     $repository                 Repository
     * @param Usecase\Project\Prepare\Repository $repository_project_prepare Repository
     * @param Tool\Auth                        $entity_auth                Authentication system
     * @param Tool\Validation                  $entity_validation          Validation system
     * @param Tool\Image                       $entity_image               Image system
     * @return void
     */
    public function __construct(Data\Project $data_project, Repository $repository, Usecase\Project\Prepare\Repository $repository_project_prepare, Tool\Auth $entity_auth, Tool\Validation $entity_validation, Tool\Image $entity_image)
    {
        $this->data_project = $data_project;
        $this->data_user = $data_project->get_author();
        $this->data_file = $data_project->get_icon();
        $this->repository = $repository;
        $this->repository_project_prepare = $repository_project_prepare;
        $this->entity_auth = $entity_auth;
        $this->entity_image = $entity_image;
        $this->entity_validation = $entity_validation;
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
        return new User($this->data_user, $this->entity_auth);
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
        return new Usecase\Project\Prepare\Proposal($this->data_project, $this->entity_validation);
    }

    private function get_project_prepare_icon()
    {
        return new Usecase\Project\Prepare\Icon($this->data_file, $this->repository_project_prepare, $this->entity_image, $this->entity_validation);
    }
}
