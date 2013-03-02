<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Edit\User $user
     * @param Eadrax\Core\Usecase\Project\Prepare\Interactor $project_prepare
     * @param Eadrax\Core\Usecase\Project\Edit\Proposal $proposal
     */
    function let($user, $proposal, $project_prepare)
    {
        $this->beConstructedWith($user, $proposal, $project_prepare);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Interactor');
    }

    function it_runs_the_interaction_chain($user, $proposal, $project_prepare)
    {
        $user->authorise()->shouldBeCalled();
        $proposal->verify_ownership($user)->shouldBeCalled();
        $project_prepare->interact()->shouldBeCalled();
        $proposal->update()->shouldBeCalled();
        $this->interact();
    }
}
