<?php
/**
 * Eadrax Context/Project/Add/Proposal.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\Project\Add;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Entity;

/**
 * Allows model_project to be cast as a proposal role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Proposal extends Model\Project implements Proposal\Requirement
{
    use Context\Interaction, Proposal\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model\Project     $model_project     Data object to copy
     * @param Repository        $repository        The repository
     * @param Entity\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Model\Project $model_project = NULL, Repository $repository = NULL, Entity\Validation $entity_validation = NULL)
    {
        if ($model_project !== NULL)
        {
            $this->assign_data($model_project);
        }

        $links = array();
        if ($repository !== NULL)
        {
            $links['repository'] = $repository;
        }

        if ($entity_validation !== NULL)
        {
            $links['entity_validation'] = $entity_validation;
        }

        $this->link($links);
    }

    /**
     * Loads data from a model.
     *
     * @param Model\Project $model_project The project model
     * @return void
     */
    public function assign_data(Model\Project $model_project)
    {
        parent::__construct(get_object_vars($model_project));
    }
}
