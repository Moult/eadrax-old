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

    /**
     * @param Eadrax\Core\Data\user $impostor
     */
    function it_does_not_authorise_users_who_do_not_own_the_project($impostor, $user, $auth)
    {
        $user->id = '24';
        $auth->get_user()->shouldBeCalled()->willReturn($impostor);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringVerify_ownership();
    }

    function it_authorises_users_who_own_the_project($user, $auth)
    {
        $user->id = '24';
        $auth->get_user()->shouldBeCalled()->willReturn($user);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringVerify_ownership();
    }
}
