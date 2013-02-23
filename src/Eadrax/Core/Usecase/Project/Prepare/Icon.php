<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

class Icon extends Data\File
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\File         $data_file         Data object to copy
     * @param Repository        $repository        Project prepare repository
     * @param Tool\Image      $entity_image      Image entity
     * @param Tool\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\File $data_file, Repository $repository, Tool\Image $entity_image, Tool\Validation $entity_validation)
    {
        foreach ($data_file as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
        $this->entity_image = $entity_image;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Validates the files status as an icon.
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
     * Uploads the icon permanently in the server.
     *
     * @return void
     */
    public function upload()
    {
        $this->repository->save_icon($this);
        $this->resize();
    }

    public function resize()
    {
        $this->entity_image->resize(50, 50);
    }

    /**
     * Sets up the validation requirements
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'metadata' => array(
                'name' => $this->get_name(),
                'type' => $this->get_mimetype(),
                'tmp_name' => $this->get_tmp_name(),
                'error' => $this->get_error(),
                'size' => $this->get_filesize()
            )
        ));
        $this->entity_validation->rule('metadata', 'upload_valid');
        $this->entity_validation->rule('metadata', 'upload_type', array('jpg', 'png'));
        $this->entity_validation->rule('metadata', 'upload_size', '1M');
    }
}
