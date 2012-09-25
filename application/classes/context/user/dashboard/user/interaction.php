<?php
/**
 * Eadrax application/classes/context/user/dashboard/user/interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Defines what the user role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Context_User_Dashboard_User_Interaction
{
    /**
     * Prove that it is allowed to view a dashboard.
     *
     * @throws Exception_Authorisation if already logged in
     * @return array
     */
    public function authorise_dashboard()
    {
        if ($this->module_auth->logged_in())
            return array(
                'username' => $this->module_auth->get_user()->username
            );
        else
            throw new Exception_Authorisation('Please login before you can view your dashboard.');
    }
}
