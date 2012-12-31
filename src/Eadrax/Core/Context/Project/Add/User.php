<?php
/**
 * Eadrax Context/Project/Add/User.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Add;
use Eadrax\Core\Context;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_user to be cast as a user role
 *
 * @package    Context
 * @subpackage Role
 */
class User extends Data\User
{
    use Context\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\User   $data_user   Data object to copy
     * @param Entity\Auth $entity_auth Auth entity
     * @return void
     */
    public function __construct(Data\User $data_user, Entity\Auth $entity_auth)
    {
        parent::__construct(get_object_vars($data_user));
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
