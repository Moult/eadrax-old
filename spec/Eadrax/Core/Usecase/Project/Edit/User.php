<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($user, $auth)
    {
        $this->beConstructedWith($user, $auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('\Eadrax\Core\Usecase\Project\Edit\User');
    }

    function it_does_not_authorise_guests($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_authorises_logged_in_users($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }
}
