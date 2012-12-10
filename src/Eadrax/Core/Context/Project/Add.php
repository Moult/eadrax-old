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
     * User role
     * @var User
     */
    public $user;

    /**
     * Proposal role
     * @var Proposal
     */
    public $proposal;

    /**
     * Repository used by subcontext project prepare
     * @var Context\Project\Prepare\Repository
     */
    private $repository_project_prepare;

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
        $this->user = new User($data_project->get_author());
        $this->proposal = new Proposal($data_project);

        $this->user->link(array(
            'proposal' => $this->proposal,
            'entity_auth' => $entity_auth
        ));

        $this->proposal->link(array(
            'repository' => $repository
        ));

        $this->repository_project_prepare = $repository_project_prepare;
        $this->entity_image = $entity_image;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
     */
    public function execute()
    {
        try
        {
            $this->interact();
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'authorisation',
                'data'   => array(
                    'errors' => array($e->getMessage())
                )
            );
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'validation',
                'data'   => array(
                    'errors' => $e->as_array()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }

    /**
     * Runs the interaction chain
     *
     * @throws Exception\Authorisation
     * @throws Exception\Validation
     */
    public function interact()
    {
        $this->user->authorise_project_add();
        $this->context_project_prepare()->interact();
        $this->proposal->submit();
    }

    /**
     * Creates a prepare project context.
     *
     * @return Context\Project\Prepare;
     */
    private function context_project_prepare()
    {
        return new Context\Project\Prepare($this->proposal, $this->repository_project_prepare, $this->entity_validation, $this->entity_image);
    }
}
