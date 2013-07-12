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
    public $id;
    private $authenticator;
    private $repository;

    public function __construct(Repository $repository, Tool\Authenticator $authenticator)
    {
        $auth_user = $authenticator->get_user();
        $this->id = $auth_user->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in.');
    }

    public function has_idol($idol_id)
    {
        return $this->repository->does_fan_have_idol($this->id, $idol_id);
    }

    public function add_idol($idol_id)
    {
        $this->repository->add_idol_to_fan($idol_id, $this->id);
    }

    public function remove_tracked_projects_by($idol_id)
    {
        $this->repository->remove_tracked_projects_authored_by_idol($this->id, $idol_id);
    }

    public function get_id()
    {
        return $this->id;
    }
}
