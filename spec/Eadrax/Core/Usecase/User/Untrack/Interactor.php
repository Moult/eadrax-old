<?php

namespace spec\Eadrax\Core\Usecase\User\Untrack;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Untrack\Fan $fan
     * @param Eadrax\Core\Usecase\User\Untrack\Idol $idol
     */
    function let($fan, $idol)
    {
        $this->beConstructedWith($fan, $idol);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Untrack\Interactor');
    }

    function it_should_run_the_interaction_chain($fan, $idol)
    {
        $fan->authorise()->shouldBeCalled();
        $idol->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $idol->add_fan()->shouldBeCalled();
        $this->interact();
    }

    function it_should_do_nothing_if_idol_already_has_fan($fan, $idol)
    {
        $fan->authorise()->shouldBeCalled();
        $idol->has_fan()->shouldBeCalled()->willReturn(TRUE);
        $idol->add_fan()->shouldNotBeCalled();
        $this->interact();
    }
}
