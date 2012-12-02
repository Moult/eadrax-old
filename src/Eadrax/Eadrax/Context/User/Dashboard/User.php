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
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Entity;
use Eadrax\Eadrax\Exception;

/**
 * Allows data_user to be cast as a guest role
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
     * @param Data $data_user Data object to copy
     * @return void
     */
    public function __construct(Data\User $data_user)
    {
        parent::__construct(get_object_vars($data_user));
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
