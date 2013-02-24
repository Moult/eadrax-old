<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;

use Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Edit\User;
use Eadrax\Core\Exception;

class Interactor
{
    private $user;
    private $proposal;
    private $project_prepare;

    public function __construct(User $user, Proposal $proposal, Project\Prepare\Interactor $project_prepare)
    {
        $this->user = $user;
        $this->proposal = $proposal;
        $this->project_prepare = $project_prepare;
    }

    public function interact()
    {
        $this->user->authorise_project_edit();
        $this->user->check_proposal_author();
        $this->project_prepare->interact();
        $this->proposal->update();
    }

    public function execute()
    {
        try
        {
            $this->interact();
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
