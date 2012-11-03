<?php
/**
 * Eadrax Context/User/Dashboard/User/Interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Dashboard\User;
use Eadrax\Eadrax\Exception;

/**
 * Defines what the user role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Interaction
{
    /**
     * Prove that it is allowed to view a dashboard.
     *
     * @throws Exception_Authorisation if already logged in
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
