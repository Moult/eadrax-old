<?php

namespace Eadrax\Core\Usecase\Update\Delete;

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
        $this->proposal->delete_thumbnail();
        $this->proposal->delete_upload();
        $this->proposal->delete();
    }
}
