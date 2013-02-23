<?php
/**
 * @license MIT
 * Full license text in LICENSE file
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
     * Auth tool
     * @var Tool\Auth
     */
    private $tool_auth;

    /**
     * Validation tool
     * @var Tool\Validation
     */
    private $tool_validation;

    /**
     * Sets up all dependencies required to run the usecase
     *
     * @param Data\User             $data_user             User data object
     * @param Repository            $repository            Repository for this context
     * @param User\Login\Repository $repository_user_login Repository for user login context
     * @param Tool\Auth           $tool_auth           Authentication system
     * @param Tool\Validation     $tool_validation     Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, User\Login\Repository $repository_user_login, Tool\Auth $tool_auth, Tool\Validation $tool_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
        $this->repository_user_login = $repository_user_login;
        $this->tool_auth = $tool_auth;
        $this->tool_validation = $tool_validation;
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
        return new Guest($this->data_user, $this->repository, $this->tool_auth, $this->tool_validation);
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
        return new User\Login\Guest($this->data_user, $this->repository_user_login, $this->tool_auth, $this->tool_validation);
    }
}
