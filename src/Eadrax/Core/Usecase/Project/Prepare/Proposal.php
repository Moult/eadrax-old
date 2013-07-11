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
    public $name;
    public $summary;
    public $website;
    private $validator;

    public function __construct(Data\Project $project, Tool\Validator $validator)
    {
        $this->name = $project->name;
        $this->summary = $project->summary;
        $this->website = $project->website;

        $this->validator = $validator;
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    private function setup_validation()
    {
        $this->validator->setup(array(
            'name' => $this->name,
            'summary' => $this->summary,
            'website' => $this->website
        ));
        $this->validator->rule('name', 'not_empty');
        $this->validator->rule('summary', 'not_empty');
        $this->validator->rule('website', 'url');
    }
}
