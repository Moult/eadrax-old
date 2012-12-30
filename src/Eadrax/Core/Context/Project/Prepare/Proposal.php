<?php
/**
 * Eadrax Context/Project/Prepare/Proposal.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Prepare;
use Eadrax\Core\Data;
use Eadrax\Core\Context;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_project to be cast as a proposal role
 *
 * @package    Context
 * @subpackage Role
 */
class Proposal extends Data\Project
{
    use Context\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\Project      $data_project      Data object to copy
     * @param Entity\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\Project $data_project, Entity\Validation $entity_validation)
    {
        parent::__construct($data_project);
        $this->entity_validation = $entity_validation;
    }

    /**
     * Validates the proposed data in this project
     *
     * @return void
     */
    public function validate_information()
    {
        $this->setup_validation();

        if ( ! $this->entity_validation->check())
            throw new Exception\Validation($this->entity_validation->errors());
    }

    /**
     * Set up the validation criteria
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'name' => $this->get_name(),
            'summary' => $this->get_summary(),
            'website' => $this->get_website()
        ));
        $this->entity_validation->rule('name', 'not_empty');
        $this->entity_validation->rule('summary', 'not_empty');
        $this->entity_validation->rule('website', 'url');
    }
}
