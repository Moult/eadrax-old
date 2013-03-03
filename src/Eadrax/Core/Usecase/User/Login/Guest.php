<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Login;

use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Guest extends Data\User
{
    private $repository;
    private $auth;
    private $validation;

    public function __construct(Data\User $user, Repository $repository, Tool\Auth $auth, Tool\Validation $validation)
    {
        $this->username = $user->username;
        $this->password = $user->password;

        $this->repository = $repository;
        $this->auth = $auth;
        $this->validation = $validation;
    }

    public function authorise()
    {
        if ($this->auth->logged_in())
            throw new Exception\Authorisation('Logged in users don\'t need to login again.');
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validation->check())
            throw new Exception\Validation($this->validation->errors());
    }

    private function setup_validation()
    {
        $this->validation->setup(array(
                'username' => $this->username,
                'password' => $this->password
            ));
        $this->validation->rule('username', 'not_empty');
        $this->validation->callback('username', array($this, 'is_existing_account'), array('username', 'password'));
    }

    public function is_existing_account($username, $password)
    {
        return $this->repository->is_existing_account($username, $password);
    }

    public function login()
    {
        $this->id = $this->auth->login($this->username, $this->password);
    }
}
