<?php
/**
 * Eadrax Context/User/Logout/Factory.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Logout;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Entity;

/**
 * Dependency injection to load all related data models, repositories, and 
 * vendor entities to prepare the Context for execution.
 *
 * @package Context
 */
class Factory extends Context\Factory
{
    /**
     * Loads the context
     *
     * @return Context\User\Logout
     */
    public function fetch()
    {
        return new Context\User\Logout(
            $this->entity_auth()
        );
    }

    /**
     * Authentiation entity
     *
     * @return Entity\Auth
     */
    public function entity_auth()
    {
        return new Entity\Auth;
    }
}
