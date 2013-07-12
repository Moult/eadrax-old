<?php

namespace spec\Eadrax\Core\Usecase\Project\Untrack;

use PHPSpec2\ObjectBehavior;

class Fan extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Usecase\Project\Untrack\Repository $repository
     */
    function let($authenticator, $repository)
    {
        $this->beConstructedWith($repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Untrack\Fan');
    }

    function it_should_be_a_user()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_authorises_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_does_not_authorise_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Data\User $fan
     */
    function it_checks_whether_it_has_an_idol($repository, $authenticator, $fan)
    {
        $fan->id = 'fan_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($fan);
        $repository->does_fan_have_idol('fan_id', 'idol_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_idol('idol_id')->shouldReturn(TRUE);
    }
}
