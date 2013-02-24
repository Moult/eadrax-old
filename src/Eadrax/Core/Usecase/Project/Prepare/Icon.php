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
    private $repository;
    private $tool_image;
    private $tool_validation;

    public function __construct(Data\File $data_file, Repository $repository, Tool\Image $tool_image, Tool\Validation $tool_validation)
    {
        foreach ($data_file as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
        $this->tool_image = $tool_image;
        $this->tool_validation = $tool_validation;
    }

    public function validate_information()
    {
        $this->setup_validation();

        if ( ! $this->tool_validation->check())
            throw new Exception\Validation($this->tool_validation->errors());
    }

    private function setup_validation()
    {
        $this->tool_validation->setup(array(
            'metadata' => array(
                'name' => $this->name,
                'type' => $this->mimetype,
                'tmp_name' => $this->tmp_name,
                'error' => $this->error_code,
                'size' => $this->filesize_in_bytes
            )
        ));
        $this->tool_validation->rule('metadata', 'upload_valid');
        $this->tool_validation->rule('metadata', 'upload_type', array('jpg', 'png'));
        $this->tool_validation->rule('metadata', 'upload_size', '1M');
    }

    public function upload()
    {
        $this->repository->save_icon($this);
        $this->resize();
    }

    public function resize()
    {
        $this->tool_image->resize(50, 50);
    }
}
