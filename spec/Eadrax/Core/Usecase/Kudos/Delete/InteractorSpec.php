<?php

namespace spec\Eadrax\Core\Usecase\Kudos\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Kudos\Delete\Nomination $nomination
     */
    function let($nomination)
    {
        $this->beConstructedWith($nomination);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Delete\Interactor');
    }

    function it_needs_to_run_the_interaction_chain($nomination)
    {
        $nomination->has_kudos()->shouldBeCalled()->willReturn(TRUE);
        $nomination->delete_kudos()->shouldBeCalled();
        $this->interact();
    }
}
