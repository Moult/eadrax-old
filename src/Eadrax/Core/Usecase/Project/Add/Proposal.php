<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Proposal extends Data\Project
{
    private $repository;

    public function __construct(Data\Project $project, Repository $repository, Tool\Auth $auth)
    {
        $this->name = $project->name;
        $this->summary = $project->summary;
        $this->author = $auth->get_user();
        $this->views = 0;
        $this->last_updated = time();

        $this->repository = $repository;
    }

    public function submit()
    {
        $this->id = $this->repository->add($this);
    }
}
