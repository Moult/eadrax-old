<?php
/**
 * Eadrax Context/Project/Add/User/Interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\Project\Add\User;
use Eadrax\Eadrax\Exception;

/**
 * Defines what the proposal role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Interaction
{
    /**
     * Prove that it is allowed to add a project.
     *
     * @throws Exception\Authorisation if not logged in
     * @return void
     */
    public function authorise_project_add()
    {
        if ($this->entity_auth->logged_in())
            return $this->load_authentication_details();
        else
            throw new Exception\Authorisation('Please login before you can add a new project.');
    }

    /**
     * Loads the authentication details of the currently logged in user into the 
     * user data model.
     *
     * @return void
     */
    public function load_authentication_details()
    {
        $authenticated_user = $this->entity_auth->get_user();
        $this->set_username($authenticated_user->username);
        $this->set_id($authenticated_user->id);

        return $this->proposal->assign_author($this);
    }
}
