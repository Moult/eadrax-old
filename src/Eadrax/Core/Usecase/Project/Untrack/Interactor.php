<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack;

class Interactor
{
    private $fan;
    private $project;
    private $user_untrack;

    public function __construct(Fan $fan, Project $project, User\Untrack $user_untrack)
    {
        $this->fan = $fan;
        $this->project = $project;
        $this->user_untrack = $user_untrack;
    }

    public function interact()
    {
        $this->fan->authorise();

        if ( ! $this->project->has_fan())
            return;

        $project_author_id = $this->project->get_project_author_id();
        if ($this->fan->has_idol($project_author_id))
        {
            $this->user_untrack->set_author_id($project_author_id);
            $this->user_untrack->fetch()->interact();
            $this->fan->track_all_projects_by_user($project_author_id);
        }
        $this->project->remove_fan();
    }
}
