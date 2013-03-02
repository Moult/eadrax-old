<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Delete;

class Interactor
{
    private $user;
    private $proposal;

    public function __construct(User $user, Proposal $proposal)
    {
        $this->user = $user;
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->user->authorise();
        $this->proposal->verify_ownership($this->user);
        $this->proposal->delete();
    }
}
