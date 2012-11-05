<?php
/**
 * Eadrax Context/Project/Add.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\Project;
use Eadrax\Eadrax\Context\Project\Add\User;
use Eadrax\Eadrax\Context\Project\Add\Proposal;
use Eadrax\Eadrax\Context\Project\Add\Repository;
use Eadrax\Eadrax\Context\Core;
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Entity;
use Eadrax\Eadrax\Exception;

/**
 * Enacts the usecase for adding a new project.
 *
 * @package Context
 */
class Add extends Core
{
    /**
     * User role
     * @var User
     */
    public $user;

    /**
     * Proposal role
     * @var Proposal
     */
    public $proposal;

    /**
     * Casts data into roles, and makes each role aware of necessary 
     * dependencies.
     *
     * @param Data\User    $data_user    User data object
     * @param User          $role_user     User role for this context
     * @param Data\Project $data_project Project data object
     * @param Proposal      $role_proposal Proposal role for this context
     * @param Repository    $repository    Repository
     * @param Entity\Auth   $entity_auth   Authentication system
     * @return void
     */
    public function __construct(Data\User $data_user, User $role_user, Data\Project $data_project, Proposal $role_proposal, Repository $repository, Entity\Auth $entity_auth)
    {
        $role_user->assign_data($data_user);
        $this->user = $role_user;

        $role_proposal->assign_data($data_project);
        $this->proposal = $role_proposal;

        $this->user->link(array(
            'proposal' => $this->proposal,
            'entity_auth' => $entity_auth
        ));

        $this->proposal->link(array(
            'repository' => $repository
        ));
    }

    /**
     * Executes the usecase.
     *
     * @return array Holds execution status, type and error information.
     */
    public function execute()
    {
        try
        {
            $this->user->authorise_project_add();
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'authorisation',
                'data'   => array(
                    'errors' => array($e->getMessage())
                )
            );
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type'   => 'validation',
                'data'   => array(
                    'errors' => $e->as_array()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
