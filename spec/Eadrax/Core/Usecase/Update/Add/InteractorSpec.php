<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Add\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     * @param Eadrax\Core\Usecase\Update\Prepare\Interactor $update_prepare
     */
    function let($project, $proposal, $update_prepare)
    {
        $this->beConstructedWith($project, $proposal, $update_prepare);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Update $prepared_proposal
     */
    function it_carries_out_the_generic_interaction_chain($prepared_proposal, $project, $proposal, $update_prepare)
    {
        $project->authorise_ownership()->shouldBeCalled();
        $update_prepare->interact()->shouldBeCalled()->willReturn($prepared_proposal);
        $proposal->load_prepared_proposal($prepared_proposal)->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $proposal->get_id()->shouldBeCalled()->willReturn('update_id');
        $project->notify_trackers()->shouldBeCalled();
        $this->interact()->shouldReturn('update_id');
    }
}
