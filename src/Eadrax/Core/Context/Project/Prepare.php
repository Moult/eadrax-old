<?php
/**
 * Eadrax Context/Project/Prepare.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project;
use Eadrax\Core\Context\Project\Prepare\User;
use Eadrax\Core\Context\Project\Prepare\Proposal;
use Eadrax\Core\Context\Project\Prepare\Icon;
use Eadrax\Core\Context\Project\Prepare\Repository;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Enacts the usecase for preparing a new project.
 *
 * Preparing a project involves validating all of its data and uploading the
 * icon.
 *
 * @package Context
 */
class Prepare extends Core
{
    /**
     * User role
     * @var Icon
     */
    public $icon;

    /**
     * Proposal role
     * @var Proposal
     */
    public $proposal;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Data\Project      $data_project      Project data object
     * @param Repository        $repository        Repository
     * @param Entity\Validation $entity_validation Validation system
     * @param Entity\Image      $entity_image      Image manipulation system
     * @return void
     */
    public function __construct(Data\Project $data_project, Repository $repository, Entity\Validation $entity_validation, Entity\Image $entity_image)
    {
        $this->proposal = new Proposal($data_project);
        $this->icon = new Icon($data_project->get_icon());

        $this->proposal->link(array(
            'repository' => $repository,
            'entity_validation' => $entity_validation,
        ));

        $this->icon->link(array(
            'proposal' => $this->proposal,
            'repository' => $repository,
            'entity_validation' => $entity_validation,
            'entity_image' => $entity_image
        ));
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
        $this->proposal->validate_information();
        if ($this->icon->exists())
        {
            $this->icon->validate_information();
            $this->icon->upload();
        }
    }
}
