<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Edit\Proposal $proposal
     * @param Eadrax\Core\Usecase\Update\Prepare\Interactor $update_prepare
     */
    function let($proposal, $update_prepare)
    {
        $this->beConstructedWith($proposal, $update_prepare);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Interactor');
    }

    /**
     * @param Eadrax\Core\Data\Update $prepared_proposal
     */
    function it_executes_the_interaction_chain($prepared_proposal, $proposal, $update_prepare)
    {
        $proposal->authorise_ownership()->shouldBeCalled();
        $update_prepare->interact()->shouldBeCalled()->willReturn($prepared_proposal);
        $proposal->load_prepared_proposal($prepared_proposal)->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $this->interact();
    }
}
