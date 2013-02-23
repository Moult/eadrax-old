<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Proposal extends Data\Project
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\Project      $data_project      Data object to copy
     * @param Tool\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\Project $data_project, Tool\Validation $entity_validation)
    {
        foreach ($data_project as $property => $value)
        {
            $this->$property = $value;
        }

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
