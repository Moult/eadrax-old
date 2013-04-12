<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Add\Author $author
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     * @param Eadrax\Core\Usecase\Update\Add\Project $project
     */
    function let($author, $proposal, $project)
    {
        $this->beConstructedWith($author, $proposal, $project);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    function it_carries_out_the_usecase($author, $proposal, $project)
    {
        $author->authorise()->shouldBeCalled();
        $proposal->validate()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $project->notify_trackers()->shouldBeCalled();
        $this->interact();
    }
}
