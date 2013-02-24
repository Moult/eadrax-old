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
    private $user;
    private $proposal;
    private $project_prepare;

    public function __construct(Proposal $proposal, User $user, Project\Prepare\Interactor $project_prepare)
    {
        $this->proposal = $proposal;
        $this->user = $user;
        $this->project_prepare = $project_prepare;
    }

    public function interact()
    {
        $this->user->authorise_project_add();
        $this->project_prepare->interact();
        $this->proposal->submit();
    }

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
