<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Delete\Proposal $proposal
     */
    function let($proposal)
    {
        $this->beConstructedWith($proposal);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Interactor');
    }

    function it_should_run_the_interaction_chain($proposal)
    {
        $proposal->authorise()->shouldBeCalled();
        $proposal->delete()->shouldBeCalled();
        $this->interact();
    }
}
