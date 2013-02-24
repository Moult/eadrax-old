<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Login;

use Eadrax\Core\Usecase;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Guest extends Data\User
{
    public function __construct(Data\User $data_user, Repository $repository, Tool\Auth $tool_auth, Tool\Validation $tool_validation)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
        $this->tool_auth = $tool_auth;
        $this->tool_validation = $tool_validation;
    }

    public function authorise_login()
    {
        if ($this->tool_auth->logged_in())
            throw new Exception\Authorisation('Logged in users don\'t need to login again.');
    }

    public function validate_information()
    {
        $this->setup_validation();

        if ( ! $this->tool_validation->check())
            throw new Exception\Validation($this->tool_validation->errors());
    }

    private function setup_validation()
    {
        $this->tool_validation->setup(array(
                'username' => $this->username,
                'password' => $this->password
            ));
        $this->tool_validation->rule('username', 'not_empty');
        $this->tool_validation->callback('username', array($this, 'is_existing_account'), array('username', 'password'));
    }

    public function is_existing_account($username, $password)
    {
        return $this->repository->is_existing_account($username, $password);
    }

    public function login()
    {
        return $this->tool_auth->login($this->username, $this->password);
    }
}
