<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Guest extends Data\User
{
    private $repository;
    private $tool_auth;
    private $tool_validation;

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

    public function authorise_registration()
    {
        if ($this->tool_auth->logged_in())
            throw new Exception\Authorisation('Logged in users cannot register new accounts.');
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
            'password' => $this->password,
            'email' => $this->email
        ));

        $this->tool_validation->rule('username', 'not_empty');
        $this->tool_validation->rule('username', 'regex', '/^[a-z_.]++$/iD');
        $this->tool_validation->rule('username', 'min_length', '4');
        $this->tool_validation->rule('username', 'max_length', '15');
        $this->tool_validation->callback('username', array($this, 'is_unique_username'), array('username'));
        $this->tool_validation->rule('password', 'not_empty');
        $this->tool_validation->rule('password', 'min_length', '6');
        $this->tool_validation->rule('email', 'not_empty');
        $this->tool_validation->rule('email', 'email');
    }

    public function is_unique_username($username)
    {
        return $this->repository->is_unique_username($username);
    }

    public function register()
    {
        $this->repository->register($this);
    }
}
