<?php

namespace spec\Eadrax\Core\Usecase\User\Untrack;

use PHPSpec2\ObjectBehavior;

class Fan extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($authenticator)
    {
        $this->beConstructedWith($authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Untrack\Fan');
    }

    function it_should_be_a_user()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_authorise_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_should_not_authorise_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }
}
