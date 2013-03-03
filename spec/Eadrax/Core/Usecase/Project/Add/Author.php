<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Author extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add\Author');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_does_not_authorise_guests($auth)
    {
        $auth->logged_in()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_checks_the_authorised_user($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }
}
