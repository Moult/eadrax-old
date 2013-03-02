<?php

namespace spec\Eadrax\Core\Usecase\Project\Delete;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($user, $auth)
    {
        $user->id = 42;
        $this->beConstructedWith($user, $auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\User');
    }

    function it_is_a_user()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_has_the_attributes_of_a_user()
    {
        $this->id->shouldBe(42);
    }

    function it_authorises_logged_in_users($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_does_not_authorise_guests($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }
}
