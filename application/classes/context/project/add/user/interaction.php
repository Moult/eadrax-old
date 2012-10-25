<?php
/**
 * Eadrax application/classes/context/project/add/user/interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Defines what the proposal role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Context_Project_Add_User_Interaction
{
    /**
     * Prove that it is allowed to add a project.
     *
     * @throws Exception_Authorisation if not logged in
     * @return void
     */
    function authorise_project_add()
    {
        if ($this->module_auth->logged_in())
            return $this->proposal->validate_information();
        else
            throw new Exception_Authorisation('Please login before you can add a new project.');
    }
}
