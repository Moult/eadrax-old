<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
use Eadrax\Core\Usecase\User;

class Interactor
{
    private $fan;
    private $project;
    private $user_track;

    public function __construct(Fan $fan, Project $project, User\Track\Interactor $user_track)
    {
        $this->fan = $fan;
        $this->project = $project;
        $this->user_track = $user_track;
    }

    public function interact()
    {
        $this->fan->authorise();
        $this->update_fan_track_status();
    }

    private function update_fan_track_status()
    {
        if ($this->fan->is_tracking_project($this->project))
            return $this->fan->remove_project($this->project);
        elseif ($this->fan->has_idol($this->project->author))
            return $this->remove_idol_and_just_track_all_other_projects();
        elseif ($this->fan->is_fan_of_all_other_projects_by_($this->project->author))
            return $this->make_project_author_an_idol_of_fan();
        else
            return $this->fan->add_project($this->project);
    }

    private function remove_idol_and_just_track_all_other_projects()
    {
        $this->fan->remove_idol($this->project->author);
        $this->fan->track_all_projects_by_author_except_for($this->project);
    }


    private function make_project_author_an_idol_of_fan()
    {
        $this->fan->untrack_all_projects_by($this->project->author);
        $this->user_track->interact();
    }

}
