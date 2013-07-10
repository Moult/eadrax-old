<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;
use Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Edit\Author;
use Eadrax\Core\Exception;

class Interactor
{
    private $author;
    private $proposal;
    private $project_prepare;

    public function __construct(Author $author, Proposal $proposal, Project\Prepare\Interactor $project_prepare)
    {
        $this->author = $author;
        $this->proposal = $proposal;
        $this->project_prepare = $project_prepare;
    }

    public function interact()
    {
        $this->author->authorise();
        $this->proposal->authorise();
        $this->project_prepare->interact();
        $this->proposal->update();
    }
}
