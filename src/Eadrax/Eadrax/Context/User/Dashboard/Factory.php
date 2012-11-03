<?php
/**
 * Eadrax Context/User/Dashboard/Factory.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\User\Dashboard;
use Eadrax\Eadrax\Context;
use Eadrax\Eadrax\Model;
use Eadrax\Eadrax\Entity;

/**
 * Dependency injection to load all related data models, repositories, and 
 * vendor modules to prepare the Context for execution.
 *
 * @package Context
 */
class Factory extends Context\Factory
{
    /**
     * Loads the context
     *
     * @return Context\User\Dashboard
     */
    public function fetch()
    {
        return new Context\User\Dashboard(
            $this->model_user(),
            $this->role_user(),
            $this->entity_auth()
        );
    }

    /**
     * Data object for users
     *
     * @return Model\User
     */
    public function model_user()
    {
        return new Model\User;
    }

    /**
     * Role object for users
     *
     * @return User
     */
    public function role_user()
    {
        return new User;
    }

    /**
     * Grabs the authentication entity
     *
     * @return Entity\Auth
     */
    public function entity_auth()
    {
        return new Entity\Auth;
    }
}
