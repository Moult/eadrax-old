<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase;
use Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Register\Interactor;
use Eadrax\Core\Usecase\User\Register\Guest;
use Eadrax\Core\Usecase\User\Register\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Register
{
    private $data_user;
    private $repository;
    private $repository_user_login;
    private $tool_auth;
    private $tool_validation;

    public function __construct(Data\User $data_user, Repository $repository, User\Login\Repository $repository_user_login, Tool\Auth $tool_auth, Tool\Validation $tool_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
        $this->repository_user_login = $repository_user_login;
        $this->tool_auth = $tool_auth;
        $this->tool_validation = $tool_validation;
    }

    public function fetch()
    {
        return new Interactor($this->get_guest(), $this->get_user_login());
    }

    private function get_guest()
    {
        return new Guest($this->data_user, $this->repository, $this->tool_auth, $this->tool_validation);
    }

    private function get_user_login()
    {
        return new User\Login\Interactor($this->get_user_login_guest());
    }

    private function get_user_login_guest()
    {
        return new User\Login\Guest($this->data_user, $this->repository_user_login, $this->tool_auth, $this->tool_validation);
    }
}
