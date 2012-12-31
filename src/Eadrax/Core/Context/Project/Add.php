<?php
/**
 * Eadrax Context/Project/Add.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project;
use Eadrax\Core\Context\Project\Add\User;
use Eadrax\Core\Context\Project\Add\Interactor;
use Eadrax\Core\Context\Project\Add\Proposal;
use Eadrax\Core\Context\Project\Add\Icon;
use Eadrax\Core\Context\Project\Add\Repository;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Context;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Enacts the usecase for adding a new project.
 *
 * @package Context
 */
class Add extends Core
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
     * @var Context\Project\Prepare\Repository
     */
    private $repository_project_prepare;

    /**
     * Auth entity
     * @var Entity\Auth
     */
    private $entity_auth;

    /**
     * Image entity used by subcontext project prepare
     * @var Entity\Image
     */
    private $entity_image;

    /**
     * Validation entity used by subcontext project prepare
     * @var Entity\Validation
     */
    private $entity_validation;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\Project                       $data_project               Project data object
     * @param Context\Project\Add\Repository     $repository                 Repository
     * @param Context\Project\Prepare\Repository $repository_project_prepare Repository
     * @param Entity\Auth                        $entity_auth                Authentication system
     * @param Entity\Validation                  $entity_validation          Validation system
     * @param Entity\Image                       $entity_image               Image system
     * @return void
     */
    public function __construct(Data\Project $data_project, Repository $repository, Context\Project\Prepare\Repository $repository_project_prepare, Entity\Auth $entity_auth, Entity\Validation $entity_validation, Entity\Image $entity_image)
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
        return new Context\Project\Prepare\Interactor(
            $this->get_project_prepare_proposal(),
            $this->get_project_prepare_icon()
        );
    }

    private function get_project_prepare_proposal()
    {
        return new Context\Project\Prepare\Proposal($this->data_project, $this->entity_validation);
    }

    private function get_project_prepare_icon()
    {
        return new Context\Project\Prepare\Icon($this->data_file, $this->repository_project_prepare, $this->entity_image, $this->entity_validation);
    }
}
