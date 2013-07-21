<?php

namespace spec\Eadrax\Core\Usecase\Kudos\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Kudos\Add\Nomination $nomination
     */
    function let($nomination)
    {
        $this->beConstructedWith($nomination);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Add\Interactor');
    }

    function it_calls_the_interaction_chain($nomination)
    {
        $nomination->has_kudos()->shouldBeCalled()->willReturn(FALSE);
        $nomination->add_kudos()->shouldBeCalled();
        $nomination->notify_author()->shouldBeCalled();
        $this->interact();
    }

    function it_does_not_do_anything_if_the_nomination_already_has_a_kudos($nomination)
    {
        $nomination->has_kudos()->shouldBeCalled()->willReturn(TRUE);
        $nomination->add_kudos()->shouldNotBeCalled();
        $nomination->notify_author()->shouldNotBeCalled();
        $this->interact();
    }
}
