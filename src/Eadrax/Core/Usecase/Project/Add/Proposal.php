<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Proposal extends Data\Project
{
    private $repository;

    public function __construct(Data\Project $data_project, Repository $repository)
    {
        foreach ($data_project as $property => $value)
        {
            $this->$property = $value;
        }
        $this->repository = $repository;
    }

    public function submit()
    {
        return $this->repository->add_project($this);
    }
}
