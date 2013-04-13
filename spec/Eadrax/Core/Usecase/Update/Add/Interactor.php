<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
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

    function it_carries_out_the_usecase($project, $proposal)
    {
        $project->authorise()->shouldBeCalled();
        $proposal->validate()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $project->notify_trackers($proposal)->shouldBeCalled();
        $this->interact();
    }

    function it_does_not_notify_trackers_if_proposal_is_private($project, $proposal)
    {
        $proposal->private = TRUE;
        $project->notify_trackers($proposal)->shouldNotBeCalled();
        $this->interact();
    }
}
