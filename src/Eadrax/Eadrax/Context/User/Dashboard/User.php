<?php
/**
 * Eadrax application/classes/Context/User/Dashboard/User.php
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
     * @param Model_User $model_user Data object to copy
     * @return void
     */
    public function __construct(Model\User $model_user = NULL)
    {
        if ($model_user !== NULL)
        {
            parent::__construct(get_object_vars($model_user));
        }
    }

    public function assign_data(Model\User $model_user)
    {
        $this->__construct($model_user);
    }
}
