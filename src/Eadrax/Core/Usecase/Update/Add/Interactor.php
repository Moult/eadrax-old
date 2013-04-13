<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

class Interactor
{
    private $project;
    private $proposal;

    public function __construct(Project $project, Proposal $proposal)
    {
        $this->project = $project;
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->project->authorise();
        $this->proposal->validate();
        $this->proposal->submit();
        $this->project->notify_trackers($this->proposal);
    }
}
