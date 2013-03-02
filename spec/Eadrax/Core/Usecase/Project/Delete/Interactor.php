<?php

namespace spec\Eadrax\Core\Usecase\Project\Delete;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Delete\User $user
     * @param Eadrax\Core\Usecase\Project\Delete\Proposal $proposal
     */
    function let($user, $proposal)
    {
        $this->beConstructedWith($user, $proposal);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\Interactor');
    }

    function it_carries_out_the_interaction_chain($user, $proposal)
    {
        $user->authorise()->shouldBeCalled();
        $proposal->verify_ownership($user)->shouldBeCalled();
        $proposal->delete()->shouldBeCalled();
        $this->interact();
    }
}
