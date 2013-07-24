<?php

namespace spec\Eadrax\Core\Usecase\Comment\Edit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Comment\Edit\Submission $submission
     */
    function let($submission)
    {
        $this->beConstructedWith($submission);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Edit\Interactor');
    }

    function it_carries_out_the_interaction_chain($submission)
    {
        $submission->authorise()->shouldBeCalled()->willReturn(TRUE);
        $submission->validate()->shouldBeCalled();
        $submission->update()->shouldBeCalled();
        $this->interact();
    }
}
