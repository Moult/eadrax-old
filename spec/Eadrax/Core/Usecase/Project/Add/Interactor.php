<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Add\Proposal $proposal
     * @param Eadrax\Core\Usecase\Project\Add\Author $author
     * @param Eadrax\Core\Usecase\Project\Prepare\Interactor $project_prepare
     */
    function let($proposal, $author, $project_prepare)
    {
        $this->beConstructedWith($proposal, $author, $project_prepare);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add\Interactor');
    }

    function it_carries_out_the_interaction_chain($proposal, $author, $project_prepare)
    {
        $proposal->id = 42;
        $author->authorise()->shouldBeCalled();
        $project_prepare->interact()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $this->interact()->shouldReturn(42);
    }
}
