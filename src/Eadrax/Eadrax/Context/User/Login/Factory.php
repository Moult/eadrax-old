<?php
/**
 * Eadrax Context/User/Login/Factory.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Login;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Entity;

/**
 * Dependency injection to load all related data models, repositories, and 
 * vendor entities to prepare the Context for execution.
 *
 * @package Context
 */
class Factory extends Context\Factory
{
    /**
     * Loads the context
     *
     * @return Context_User_Login
     */
    public function fetch()
    {
        return new Context\User\Login(
            $this->model_user(),
            $this->role_guest(),
            $this->repository(),
            $this->entity_auth(),
            $this->entity_validation()
        );
    }

    /**
     * Data object for users
     *
     * @return Model\User
     */
    public function model_user()
    {
        return new Model\User(array(
            'username' => $this->get_data('username'),
            'password' => $this->get_data('password'),
            'email' => $this->get_data('email')
        ));
    }

    /**
     * This is an authentication entity.
     *
     * @return Entity\Auth
     */
    public function entity_auth()
    {
        return new Entity\Auth;
    }

    /**
     * Grabs the Guest role
     *
     * @return Guest
     */
    public function role_guest()
    {
        return new Guest;
    }

    /**
     * Gets the repository for this context.
     *
     * @return Repository
     */
    public function repository()
    {
        return new Repository;
    }

    /**
     * This is a validation entity.
     *
     * @return Entity\Validation
     */
    public function entity_validation()
    {
        return new Entity\Validation;
    }
}
