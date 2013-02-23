<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Usecase;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class User extends Data\User
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\User   $data_user   Data object to copy
     * @param Tool\Auth $entity_auth Auth entity
     * @return void
     */
    public function __construct(Data\User $data_user, Tool\Auth $entity_auth)
    {
        foreach ($data_user as $property => $value)
        {
            $this->$property = $value;
        }

        $this->entity_auth = $entity_auth;
    }

    /**
     * Prove that it is allowed to add a project.
     *
     * @throws Exception\Authorisation if not logged in
     * @return bool
     */
    public function authorise_project_add()
    {
        if ($this->entity_auth->logged_in())
            return TRUE;
        else
            throw new Exception\Authorisation('Please login before you can add a new project.');
    }
}
