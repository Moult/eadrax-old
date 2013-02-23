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
    /**
     * Usecase repository
     * @var Repository
     */
    private $repository;

    /**
     * Auth entity
     * @var Tool\Auth
     */
    private $entity_auth;

    /**
     * Validation entity
     * @var Tool\Validation
     */
    private $entity_validation;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\User         $data_user         Data object to copy
     * @param Repository        $repository        context repository
     * @param Tool\Auth       $entity_auth       Auth entity
     * @param Tool\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Tool\Auth $entity_auth, Tool\Validation $entity_validation)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
        $this->entity_auth = $entity_auth;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Prove that it is allowed to register an account.
     *
     * @throws Exception\Authorisation if already logged in
     * @return void
     */
    public function authorise_registration()
    {
        if ($this->entity_auth->logged_in())
            throw new Exception\Authorisation('Logged in users cannot register new accounts.');
    }

    /**
     * Makes sure our signup details are valid.
     *
     * @throws Exception\Validation
     * @return void
     */
    public function validate_information()
    {
        $this->setup_validation();

        if ( ! $this->entity_validation->check())
            throw new Exception\Validation($this->entity_validation->errors());
    }

    /**
     * Checks whether or not a username is unique.
     *
     * @param string $username The username to check.
     * @return bool
     */
    public function is_unique_username($username)
    {
        return $this->repository->is_unique_username($username);
    }

    /**
     * Registers the guest as a new user.
     *
     * @return void
     */
    public function register()
    {
        $this->repository->register($this);
    }

    /**
     * Sets up validation rules for checking user details.
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email
        ));

        $this->entity_validation->rule('username', 'not_empty');
        $this->entity_validation->rule('username', 'regex', '/^[a-z_.]++$/iD');
        $this->entity_validation->rule('username', 'min_length', '4');
        $this->entity_validation->rule('username', 'max_length', '15');
        $this->entity_validation->callback('username', array($this, 'is_unique_username'), array('username'));
        $this->entity_validation->rule('password', 'not_empty');
        $this->entity_validation->rule('password', 'min_length', '6');
        $this->entity_validation->rule('email', 'not_empty');
        $this->entity_validation->rule('email', 'email');
    }
}
