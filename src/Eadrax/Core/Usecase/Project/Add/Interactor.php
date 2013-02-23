<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;

use Eadrax\Core\Usecase\Project;
use Eadrax\Core\Exception;

class Interactor
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
     * Sets up role dependencies
     *
     * @return void
     */
    public function __construct(Proposal $proposal, User $user, Project\Prepare\Interactor $project_prepare)
    {
        $this->proposal = $proposal;
        $this->user = $user;
        $this->project_prepare = $project_prepare;
    }

    /**
     * Carries out the interaction chain of the usecase
     *
     * @return void
     */
    public function interact()
    {
        $this->user->authorise_project_add();
        $this->project_prepare->interact();
        $this->proposal->submit();
    }

    /**
     * Runs interaction, generating a results array
     *
     * @return array
     */
    public function execute()
    {
        try
        {
            $this->interact();
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'validation',
                'data' => array(
                    'errors' => $e->get_errors()
                )
            );
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'authorisation',
                'data' => array(
                    'errors' => array($e->getMessage())
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
