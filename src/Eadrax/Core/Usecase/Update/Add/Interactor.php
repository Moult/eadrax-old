<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

class Interactor
{
    private $author;
    private $proposal;
    private $project;

    public function __construct(Author $author, Proposal $proposal, Project $project)
    {
        $this->author = $author;
        $this->proposal = $proposal;
        $this->project = $project;
    }

    public function interact()
    {
        $this->author->authorise();
        $this->proposal->validate();
        $this->proposal->submit();
        $this->project->notify_trackers();
    }
}
