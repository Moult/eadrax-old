<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Usecase\Project;
use Eadrax\Core\Exception;

class Interactor
{
    private $author;
    private $proposal;
    private $project_prepare;

    public function __construct(Proposal $proposal, Author $author, Project\Prepare\Interactor $project_prepare)
    {
        $this->proposal = $proposal;
        $this->author = $author;
        $this->project_prepare = $project_prepare;
    }

    public function interact()
    {
        $this->author->authorise();
        $this->project_prepare->interact();
        $this->proposal->submit();
        return $this->proposal->id;
    }
}
