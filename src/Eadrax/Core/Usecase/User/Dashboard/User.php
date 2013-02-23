<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Dashboard;

use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class User extends Data\User
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data $data_user Data object to copy
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
     * Prove that it is allowed to view a dashboard.
     *
     * @throws Exception\Authorisation if already logged in
     * @return array
     */
    public function authorise_dashboard()
    {
        if ($this->entity_auth->logged_in())
            return array(
                'username' => $this->entity_auth->get_user()->username
            );
        else
            throw new Exception\Authorisation('Please login before you can view your dashboard.');
    }
}
