<?php

namespace spec\Eadrax\Core\Usecase\User\Untrack;

use PHPSpec2\ObjectBehavior;

class Idol extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $fan
     * @param Eadrax\Core\Data\User $idol
     * @param Eadrax\Core\Usecase\User\Untrack\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($fan, $idol, $repository, $authenticator)
    {
        $idol->id = 'idol_id';
        $fan->id = 'fan_id';
        $authenticator->get_user()->willReturn($fan);
        $this->beConstructedWith($idol, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Untrack\Idol');
    }

    function it_should_be_a_user()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_checks_whether_or_not_it_has_a_fan($repository)
    {
        $repository->does_idol_have_fan('idol_id', 'fan_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_fan()->shouldReturn(TRUE);
    }

    function it_can_add_a_fan($repository)
    {
        $repository->add_fan_to_idol('fan_id', 'idol_id')->shouldBeCalled();
        $this->add_fan();
    }
}
