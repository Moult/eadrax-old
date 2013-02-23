<?php
/**
 * Eadrax Usecase/User/Login.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\User;
use Eadrax\Core\Usecase\Core;
use Eadrax\Core\Usecase\User\Login\Interactor;
use Eadrax\Core\Usecase\User\Login\Guest;
use Eadrax\Core\Usecase\User\Login\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

/**
 * Enacts the usecase for user login.
 *
 * @package Usecase
 */
class Login extends Core
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
     * Sets up all usecase dependencies
     *
     * @param Data\User         $data_user         User data object
     * @param Repository        $repository        The repository
     * @param Tool\Auth       $entity_auth       Authentication system
     * @param Tool\Validation $entity_validation Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Tool\Auth $entity_auth, Tool\Validation $entity_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
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
        return new Interactor($this->get_guest());
    }

    /**
     * Retrieves the guest role
     *
     * @return Guest
     */
    private function get_guest()
    {
        return new Guest($this->data_user, $this->repository, $this->entity_auth, $this->entity_validation);
    }
}
