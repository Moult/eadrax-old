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
        $this->user->authorise();
        $this->proposal->verify_ownership($this->user);
        $this->project_prepare->interact();
        $this->proposal->update();
    }
}
