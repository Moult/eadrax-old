<?php

namespace spec\Eadrax\Core\Usecase\Project\Delete;

use PhpSpec\ObjectBehavior;

class AuthorSpec extends ObjectBehavior
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
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\Author');
    }

    function it_is_a_user()
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
}
