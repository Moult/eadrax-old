<?php

namespace spec\Eadrax\Core\Usecase\Comment\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Comment\Delete\Submission $submission
     */
    function let($submission)
    {
        $this->beConstructedWith($submission);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Delete\Interactor');
    }

    function it_runs_the_interaction_chain($submission)
    {
        $submission->authorise()->shouldBeCalled();
        $submission->delete()->shouldBeCalled();
        $this->interact();
    }
}
