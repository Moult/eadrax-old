<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;
use Eadrax\Core\Tool;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;

class User extends Data\User
{
    private $auth;
    private $repository;

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
}
