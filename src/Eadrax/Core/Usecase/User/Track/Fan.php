<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;
use Eadrax\Core\Tool;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    private $auth;
    private $repository;

    public function __construct(Repository $repository, Tool\Auth $auth)
    {
        $auth_user = $auth->get_user();
        $this->id = $auth_user->id;
        $this->username = $auth_user->username;

        $this->repository = $repository;
        $this->auth = $auth;
    }

    public function authorise()
    {
        if ( ! $this->auth->logged_in())
            throw new Exception\Authorisation('You need to be logged in.');
    }

    public function has_idol(Idol $idol)
    {
        return $this->repository->is_fan_of($this, $idol);
    }

    public function add_idol($idol)
    {
        $this->repository->add_idol($this, $idol);
    }

    public function remove_idol($idol)
    {
        $this->repository->remove_idol($this, $idol);
    }

    public function remove_tracked_projects_by($idol)
    {
        $this->repository->remove_tracked_projects_by($this, $idol);
    }
}
