<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    private $repository;
    private $auth;

    public function __construct(Repository $repository, Tool\Auth $auth)
    {
        $auth_user = $auth->get_user();
        $this->id = $auth_user->id;

        $this->repository = $repository;
        $this->auth = $auth;
    }

    public function authorise()
    {
        if ( ! $this->auth->logged_in())
            throw new Exception\Authorisation('You need to be logged in');
    }

    public function is_tracking_project(Project $project)
    {
        return $this->repository->is_user_tracking_project($this, $project);
    }

    public function remove_project(Project $project)
    {
        $this->repository->remove_project($this, $project);
    }

    public function has_idol(Data\User $idol)
    {
        return $this->repository->is_fan_of($this, $idol);
    }

    public function remove_idol($idol)
    {
        $this->repository->remove_idol($this, $idol);
    }

    public function track_all_projects_by_author_except_for(Project $project)
    {
        $projects = $this->repository->get_projects_by_author($project->author);
        array_splice($projects, array_search($project, $projects), 1);
        $this->repository->add_projects($this, $projects);
    }

    public function is_fan_of_all_other_projects_by(Data\User $author)
    {
        if ($this->repository->number_of_projects_by($author) === $this->repository->number_of_projects_tracked_by($this, $author) + 1)
            return TRUE;
        else
            return FALSE;
    }

    public function untrack_all_projects_by(Data\User $author)
    {
        $this->repository->remove_projects_by_author($this, $author);
    }

    public function add_project(Project $project)
    {
        $this->repository->add_project($this, $project);
    }
}
