<?php
/**
 * Eadrax Usecase/User/Register.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase;
use Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\User\Register\Interactor;
use Eadrax\Core\Usecase\User\Register\Guest;
use Eadrax\Core\Usecase\User\Register\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

/**
 * Enacts the usecase for user registration.
 *
 * @package Usecase
 */
class Register
{
    /**
     * User data
     * @var Data\User
     */
    private $data_user;

    /**
     * Usecase repository
     * @var Repository
     */
    private $repository;

    /**
     * User login repository
     * @var User\Login\Repository
     */
    private $repository_user_login;

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
     * Sets up all dependencies required to run the usecase
     *
     * @param Data\User             $data_user             User data object
     * @param Repository            $repository            Repository for this context
     * @param User\Login\Repository $repository_user_login Repository for user login context
     * @param Tool\Auth           $entity_auth           Authentication system
     * @param Tool\Validation     $entity_validation     Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, User\Login\Repository $repository_user_login, Tool\Auth $entity_auth, Tool\Validation $entity_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
        $this->repository_user_login = $repository_user_login;
        $this->entity_auth = $entity_auth;
        $this->entity_validation = $entity_validation;
    }

    /**
     * Fetches the interactor
     *
     * @return Interactor
     */
    public function fetch()
    {
        return new Interactor($this->get_guest(), $this->get_user_login());
    }

    /**
     * Gets a guest role
     *
     * @return Guest
     */
    private function get_guest()
    {
        return new Guest($this->data_user, $this->repository, $this->entity_auth, $this->entity_validation);
    }

    /**
     * Gets a user login interactor
     *
     * @return User\Login\Interactor
     */
    private function get_user_login()
    {
        return new User\Login\Interactor($this->get_user_login_guest());
    }

    /**
     * Gets a user login guest role
     *
     * @return User\Login\Guest
     */
    private function get_user_login_guest()
    {
        return new User\Login\Guest($this->data_user, $this->repository_user_login, $this->entity_auth, $this->entity_validation);
    }
}
