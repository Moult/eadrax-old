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

namespace Eadrax\Core\Context\User;
use Eadrax\Core\Context\Core;
use Eadrax\Core\Entity;

/**
 * Enacts the usecase for user logout.
 *
 * @package Context
 */
class Logout extends Core
{
    /**
     * Auth entity. This context does not require a role.
     * @var Entity\Auth
     */
    public $entity_auth;

    /**
     * Casts data into roles, and makes each role aware of necessary
     * dependencies.
     *
     * @param Entity\Auth $entity_auth Authentication system
     * @return void
     */
    public function __construct($entity_auth)
    {
        $this->entity_auth = $entity_auth;
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
     */
    public function execute()
    {
        $this->interact();

        return array(
            'status' => 'success'
        );
    }

    /**
     * Runs the interaction chain.
     *
     * @return void
     */
    public function interact()
    {
        $this->entity_auth->logout();
    }
}
