<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    public $fan;
    private $repository;
    private $authenticator;

    public function __construct(Repository $repository, Tool\Authenticator $authenticator)
    {
        $fan = $authenticator->get_user();
        $this->id = $fan->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in to untrack a project');
    }

    public function has_idol($idol_id)
    {
        return $this->repository->does_fan_have_idol($this->id, $idol_id);
    }

    public function track_all_projects_by_user($project_author_id)
    {
        $projects = $this->repository->get_project_ids_by_author($project_author_id);
        foreach ($projects as $project_id)
        {
            $this->repository->add_fan_to_project($this->id, $project_id);
        }
    }
}
