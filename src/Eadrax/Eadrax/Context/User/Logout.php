<?php
/**
 * Eadrax Context/User/Logout.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User;
use Eadrax\Eadrax\Context\Core;

/**
 * Enacts the usecase for user logout.
 *
 * @package Context
 */
class Logout extends Core
{
    /**
     * Auth module. This context does not require a role.
     * @var Auth
     */
    public $module_auth;

    /**
     * Casts data models into roles, and makes each role aware of necessary 
     * dependencies.
     *
     * @param Module_Auth $module_auth Authentication system
     * @return void
     */
    public function __construct($module_auth)
    {
        $this->module_auth = $module_auth;
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
     */
    public function execute()
    {
        $this->module_auth->logout();

        return array(
            'status' => 'success'
        );
    }
}
