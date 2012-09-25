<?php
/**
 * Eadrax application/classes/context/user/dashboard/user.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Allows model_user to be cast as a guest role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Context_User_Dashboard_User extends Model_User implements Context_User_Dashboard_User_Requirement
{
    use Context_Interaction, Context_User_Dashboard_User_Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model_User $model_user Data object to copy
     * @return void
     */
    public function __construct(Model_User $model_user)
    {
        parent::__construct(get_object_vars($model_user));
    }
}
