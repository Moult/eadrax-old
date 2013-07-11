<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;

class Interactor
{
    private $author;
    private $fan;
    private $project;
    private $user_track;

    public function __construct(Author $author, Fan $fan, Project $project, User\Track $user_track)
    {
        $this->author = $author;
        $this->fan = $fan;
        $this->project = $project;
        $this->user_track = $user_track;
    }

    public function interact()
    {
        $this->fan->authorise();

        if ($this->project->has_fan())
            return;

        $author_id = $this->author->get_id();
        if ($this->is_fan_of_all_projects_by_author($author_id))
        {
            $this->author->remove_fan_from_all_projects();
            $this->user_track->set_author_id($author_id);
            $this->user_track->fetch()->interact();
        }
        else
        {
            $this->project->add_fan();
            $this->author->notify_about_new_project_tracker($this->project->get_id());
        }
    }

    private function is_fan_of_all_projects_by_author($author_id)
    {
        return (bool) ($this->author->get_number_of_projects() === $this->fan->get_number_of_projects_owned_by_author($author_id) + 1);
    }
}
