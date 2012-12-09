<?php

namespace Eadrax\Core\Context\Project\Edit;

use \Eadrax\Core\Context\Interaction;
use \Eadrax\Core\Data;

class Proposal extends Data\Project
{
    use Interaction;

    public function __construct(Data\Project $data_project)
    {
        parent::__construct(get_object_vars($data_project));
    }
}
