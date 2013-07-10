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
    public $id;
    public $name;
    public $summary;
    public $author;
    private $repository;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->name = $project->name;
        $this->summary = $project->summary;
        $this->author = $authenticator->get_user();
        $this->repository = $repository;
    }

    public function submit()
    {
        $this->id = $this->repository->add(
            $this->name,
            $this->summary,
            $this->author->id
        );
    }
}
