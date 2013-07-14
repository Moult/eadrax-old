<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Usecase;
use Eadrax\Core\Data;

class Interactor
{
    private $project;
    private $proposal;
    private $update_prepare;

    public function __construct(Project $project, Proposal $proposal, Usecase\Update\Prepare\Interactor $update_prepare)
    {
        $this->project = $project;
        $this->proposal = $proposal;
        $this->update_prepare = $update_prepare;
    }

    public function interact()
    {
        $this->project->authorise_ownership();
        $this->proposal->load_prepared_proposal($this->update_prepare->interact());
        $this->proposal->submit();
        $this->project->notify_trackers();
        return $this->proposal->get_id();
    }
}
