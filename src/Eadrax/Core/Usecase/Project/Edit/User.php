<?php
/**
 * Eadrax Usecase/Project/Edit/User.php
 *
 * @package   Role
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\Project\Edit;

use Eadrax\Core\Data;
use Eadrax\Core\Exception;
use Eadrax\Core\Tool;

/**
 * User role
 *
 * @package Role
 */
class User extends Data\User
{
    /**
     * Copies all the properties of a data object
     *
     * @param Data\User $data_user The user data to copy
     * @return void
     */
    public function __construct(Data\User $data_user, Tool\Auth $entity_auth)
    {
        parent::__construct($data_user);
        $this->entity_auth = $entity_auth;
    }

    /**
     * Authorises that the user is allowed to edit the project.
     *
     * Checks whether or not they are logged in and they own the project
     *
     * @throws Exception\Authorisation
     * @return bool
     */
    public function authorise_project_edit()
    {
        if ($this->entity_auth->logged_in())
            return TRUE;
        else
            throw new Exception\Authorisation('You need to be logged in to edit a project.');
    }

    /**
     * Checks whether or not we own the project.
     *
     * @throws Exception\Authorisation
     * @return bool
     */
    public function check_proposal_author()
    {
        if ($this->get_id() === $this->entity_auth->get_user()->get_id())
            return TRUE;
        else
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }
}
