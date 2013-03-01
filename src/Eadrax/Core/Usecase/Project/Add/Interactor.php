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
        $this->user->authorise();
        $this->project_prepare->interact();
        $this->proposal->submit();
    }
}
