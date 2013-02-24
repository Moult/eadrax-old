<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Login\Interactor;
use Eadrax\Core\Usecase\User\Login\Guest;
use Eadrax\Core\Usecase\User\Login\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Login
{
    private $data_user;
    private $repository;
    private $tool_auth;
    private $tool_validation;

    public function __construct(Data\User $data_user, Repository $repository, Tool\Auth $tool_auth, Tool\Validation $tool_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
        $this->tool_auth = $tool_auth;
        $this->tool_validation = $tool_validation;
    }

    public function fetch()
    {
        return new Interactor($this->get_guest());
    }

    private function get_guest()
    {
        return new Guest($this->data_user, $this->repository, $this->tool_auth, $this->tool_validation);
    }
}
