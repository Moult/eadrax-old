<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Data;

class Proposal extends Data\Project
{
    private $repository;

    public function __construct(Data\Project $project, Repository $repository)
    {
        foreach ($project as $property => $value)
        {
            $this->$property = $value;
        }
        $this->repository = $repository;
    }

    public function submit()
    {
        $this->id = $this->repository->add($this);
    }
}
