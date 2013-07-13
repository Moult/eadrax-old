<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

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

        if ($this->proposal instanceof Data\Text)
            return $this->interact_text();
    }

    private function interact_text()
    {
        return $this->proposal->submit();
    }
}
