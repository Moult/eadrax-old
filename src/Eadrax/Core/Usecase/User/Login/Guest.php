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
    public $username;
    public $password;
    private $repository;
    private $authenticator;
    private $validator;

    public function __construct(Data\User $user, Repository $repository, Tool\Authenticator $authenticator, Tool\Validator $validator)
    {
        $this->username = $user->username;
        $this->password = $user->password;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->validator = $validator;
    }

    public function authorise()
    {
        if ($this->authenticator->logged_in())
            throw new Exception\Authorisation('Logged in users don\'t need to login again.');
    }

    public function validate()
    {
        $this->setup_validation();

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    private function setup_validation()
    {
        $this->validator->setup(array(
                'username' => $this->username,
                'password' => $this->password
            ));
        $this->validator->rule('username', 'not_empty');
        $this->validator->rule('password', 'not_empty');
        $this->validator->callback('username', array($this, 'is_existing_account'), array('username', 'password'));
    }

    public function is_existing_account($username, $password)
    {
        return $this->repository->is_existing_account($username, $password);
    }

    public function login()
    {
        $this->id = $this->authenticator->login($this->username, $this->password);
    }
}
