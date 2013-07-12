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
    public $username;
    public $password;
    public $email;
    private $repository;
    private $authenticator;
    private $validator;

    public function __construct(Data\User $user, Repository $repository, Tool\Authenticator $authenticator, Tool\Validator $validator)
    {
        $this->username = $user->username;
        $this->password = $user->password;
        $this->email = $user->email;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->validator = $validator;
    }

    public function authorise()
    {
        if ($this->authenticator->logged_in())
            throw new Exception\Authorisation('Logged in users cannot register new accounts.');
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
            'password' => $this->password,
            'email' => $this->email
        ));

        $this->validator->rule('username', 'not_empty');
        $this->validator->rule('username', 'regex', '/^[a-z_.]++$/iD');
        $this->validator->rule('username', 'min_length', '3');
        $this->validator->rule('username', 'max_length', '15');
        $this->validator->callback('username', array($this, 'is_unique_username'), array('username'));
        $this->validator->rule('password', 'not_empty');
        $this->validator->rule('password', 'min_length', '6');
        $this->validator->rule('email', 'not_empty');
        $this->validator->rule('email', 'email');
    }

    public function is_unique_username($username)
    {
        return $this->repository->is_unique_username($username);
    }

    public function register()
    {
        $this->repository->register($this->username, $this->password, $this->email);
    }
}
