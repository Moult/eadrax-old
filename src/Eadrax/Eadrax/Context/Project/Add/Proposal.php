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
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Entity;

/**
 * Allows data_project to be cast as a proposal role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Proposal extends Data\Project implements Proposal\Requirement
{
    use Context\Interaction, Proposal\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\Project     $data_project     Data object to copy
     * @param Repository        $repository        The repository
     * @param Entity\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\Project $data_project = NULL, Repository $repository = NULL, Entity\Validation $entity_validation = NULL)
    {
        if ($data_project !== NULL)
        {
            $this->assign_data($data_project);
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
     * Loads data from a data.
     *
     * @param Data\Project $data_project The project data
     * @return void
     */
    public function assign_data(Data\Project $data_project)
    {
        parent::__construct(get_object_vars($data_project));
    }
}
