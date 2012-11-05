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

namespace Eadrax\Eadrax\Context\Project\Add;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Entity;

/**
 * Allows model_user to be cast as a user role
 *
 * @package    Context
 * @subpackage Role
 */ 
class User extends Model\User implements User\Requirement
{
    use Context\Interaction, User\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model\User  $model_user  Data object to copy
     * @param Proposal    $role_proposal 
     * @param Entity\Auth $entity_auth The authentication entity
     * @return void
     */
    public function __construct(Model\User $model_user = NULL, Proposal $role_proposal = NULL, Entity\Auth $entity_auth = NULL)
    {
        if ($model_user !== NULL)
        {
            $this->assign_data($model_user);
        }

        $links = array();

        if ($role_proposal !== NULL)
        {
            $links['proposal'] = $role_proposal;
        }

        if ($entity_auth !== NULL)
        {
            $links['entity_auth'] = $entity_auth;
        }

        $this->link($links);
    }

    /**
     * Loads in data from a model
     *
     * @param Model\User $model_user The user model to load.
     * @return void
     */
    public function assign_data(Model\User $model_user)
    {
        parent::__construct(get_object_vars($model_user));
    }
}
