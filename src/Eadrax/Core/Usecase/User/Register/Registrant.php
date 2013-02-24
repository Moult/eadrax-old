<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Register;

use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Registrant extends Data\User
{
    private $repository;
    private $auth;
    private $validation;

    public function __construct(Data\User $user, Repository $repository, Tool\Auth $auth, Tool\Validation $validation)
    {
        foreach ($user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
        $this->auth = $auth;
        $this->validation = $validation;
    }

    public function authorise()
    {
        if ($this->auth->logged_in())
            throw new Exception\Authorisation('Logged in users cannot register new accounts.');
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
            'password' => $this->password,
            'email' => $this->email
        ));

        $this->validation->rule('username', 'not_empty');
        $this->validation->rule('username', 'regex', '/^[a-z_.]++$/iD');
        $this->validation->rule('username', 'min_length', '3');
        $this->validation->rule('username', 'max_length', '15');
        $this->validation->callback('username', array($this, 'is_unique_username'), array('username'));
        $this->validation->rule('password', 'not_empty');
        $this->validation->rule('password', 'min_length', '6');
        $this->validation->rule('email', 'not_empty');
        $this->validation->rule('email', 'email');
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
