<?php
/**
 * Eadrax Usecase/User/Login/Guest.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User\Login;
use Eadrax\Core\Usecase;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_user to be cast as a guest role
 *
 * @package    Usecase
 * @subpackage Role
 */
class Guest extends Data\User
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\User         $data_user         Data object to copy
     * @param Repository        $repository        Repository
     * @param Entity\Auth       $entity_auth       Auth entity
     * @param Entity\Validation $entity_validation Validation entity
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Entity\Auth $entity_auth, Entity\Validation $entity_validation)
    {
        parent::__construct($data_user);
        $this->repository = $repository;
        $this->entity_auth = $entity_auth;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Prove that it is allowed to login an account.
     *
     * @throws Exception\Authorisation if already logged in
     * @return void
     */
    public function authorise_login()
    {
        if ($this->entity_auth->logged_in())
            throw new Exception\Authorisation('Logged in users don\'t need to login again.');
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
     * @param string $password The password to check.
     * @return bool
     */
    public function is_existing_account($username, $password)
    {
        return $this->repository->is_existing_account($username, $password);
    }

    /**
     * Logs the guest into the system.
     *
     * @return void
     */
    public function login()
    {
        return $this->entity_auth->login($this->username, $this->password);
    }

    /**
     * Sets up validation rules.
     *
     * @return void
     */
    public function setup_validation()
    {
        $this->entity_validation->setup(array(
                'username' => $this->get_username(),
                'password' => $this->get_password()
            ));
        $this->entity_validation->rule('username', 'not_empty');
        $this->entity_validation->callback('username', array($this, 'is_existing_account'), array('username', 'password'));
    }
}
