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
    private $tool_validation;

    public function __construct(Data\Project $data_project, Tool\Validation $tool_validation)
    {
        foreach ($data_project as $property => $value)
        {
            $this->$property = $value;
        }

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
            'name' => $this->name,
            'summary' => $this->summary,
            'website' => $this->website
        ));
        $this->tool_validation->rule('name', 'not_empty');
        $this->tool_validation->rule('summary', 'not_empty');
        $this->tool_validation->rule('website', 'url');
    }
}
