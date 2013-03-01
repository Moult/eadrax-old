<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Proposal extends Data\Project
{
    private $validation;

    public function __construct(Data\Project $project, Tool\Validation $validation)
    {
        foreach ($project as $property => $value)
        {
            $this->$property = $value;
        }

        $this->validation = $validation;
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validation->check())
            throw new Exception\Validation($this->validation->errors());
    }

    private function setup_validation()
    {
        $this->validation->setup(array(
            'name' => $this->name,
            'summary' => $this->summary,
            'website' => $this->website
        ));
        $this->validation->rule('name', 'not_empty');
        $this->validation->rule('summary', 'not_empty');
        $this->validation->rule('website', 'url');
    }
}
