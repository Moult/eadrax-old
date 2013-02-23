<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User;

use Eadrax\Core\Usecase\User\Login\Interactor;
use Eadrax\Core\Usecase\User\Login\Guest;
use Eadrax\Core\Usecase\User\Login\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Login
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
     * Sets up all usecase dependencies
     *
     * @param Data\User         $data_user         User data object
     * @param Repository        $repository        The repository
     * @param Tool\Auth       $tool_auth       Authentication system
     * @param Tool\Validation $tool_validation Validation system
     * @return void
     */
    public function __construct(Data\User $data_user, Repository $repository, Tool\Auth $tool_auth, Tool\Validation $tool_validation)
    {
        $this->data_user = $data_user;
        $this->repository = $repository;
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
        return new Interactor($this->get_guest());
    }

    /**
     * Retrieves the guest role
     *
     * @return Guest
     */
    private function get_guest()
    {
        return new Guest($this->data_user, $this->repository, $this->tool_auth, $this->tool_validation);
    }
}
