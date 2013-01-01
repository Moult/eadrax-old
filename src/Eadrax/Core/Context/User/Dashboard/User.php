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

namespace Eadrax\Core\Context\User\Dashboard;
use Eadrax\Core\Data;
use Eadrax\Core\Context;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_user to be cast as a guest role
 *
 * @package    Context
 * @subpackage Role
 */
class User extends Data\User
{
    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data $data_user Data object to copy
     * @return void
     */
    public function __construct(Data\User $data_user, Entity\Auth $entity_auth)
    {
        parent::__construct($data_user);
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
                'username' => $this->entity_auth->get_user()->get_username()
            );
        else
            throw new Exception\Authorisation('Please login before you can view your dashboard.');
    }
}
