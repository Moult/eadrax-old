<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Add\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     */
    function let($project, $proposal)
    {
        $this->beConstructedWith($project, $proposal);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    function it_carries_out_the_generic_interaction_chain($project, $proposal)
    {
        $project->authorise_ownership()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $proposal->get_id()->shouldBeCalled()->willReturn('update_id');
        $project->notify_trackers()->shouldBeCalled();
        $this->interact()->shouldReturn('update_id');
    }
}
