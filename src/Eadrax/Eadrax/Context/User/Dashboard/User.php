<?php
/**
 * Eadrax Context/User/Dashboard/User.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Dashboard;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Entity;

/**
 * Allows model_user to be cast as a guest role
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
     * @param Entity\Auth $entity_auth The authentication entity
     * @return void
     */
    public function __construct(Model\User $model_user = NULL, Entity\Auth $entity_auth = NULL)
    {
        if ($model_user !== NULL)
        {
            $this->assign_data($model_user);
        }

        if ($entity_auth !== NULL)
        {
            $this->link(array(
                'entity_auth' => $entity_auth
            ));
        }
    }

    /**
     * Assigns data into the role from a data object
     *
     * @param Model\User $model_user Data object to copy
     * @return void
     */
    public function assign_data(Model\User $model_user)
    {
        parent::__construct(get_object_vars($model_user));
    }
}
