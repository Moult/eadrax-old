<?php
/**
 * Eadrax Context/Project/Edit.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project;

use Eadrax\Core\Context\Core;
use Eadrax\Core\Context\Project\Edit\User;
use Eadrax\Core\Context\Project\Edit\Interactor;
use Eadrax\Core\Context\Project\Edit\Proposal;
use Eadrax\Core\Context\Project\Edit\Repository;
use Eadrax\Core\Data;
use Eadrax\Core\Entity;

class Edit extends Core
{
    /**
     * Project data
     * @var Data\Project
     */
    private $data_project;

    /**
     * Auth entity
     * @var Entity\Auth
     */
    private $entity_auth;

    /**
     * Assigns data to roles and establishes role relationships
     *
     * @param Data\Project $data_project The project you want to edit.
     * @param Entity\Auth  $entity_auth  Authentication entity
     * @return void
     */
    function __construct(Data\Project $data_project, Entity\Auth $entity_auth)
    {
        $this->data_project = $data_project;
        $this->entity_auth = $entity_auth;
    }

    public function fetch()
    {
        return new Interactor($this->get_user());
    }

    private function get_user()
    {
        return new User($this->data_project->get_author(), $this->entity_auth);
    }
}
