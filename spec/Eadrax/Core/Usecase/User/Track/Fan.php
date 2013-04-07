<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PHPSpec2\ObjectBehavior;

class Fan extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Track\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Data\User $user
     */
    function let($repository, $auth, $user)
    {
        $user->id = 'id';
        $user->username = 'Barfoo';
        $auth->get_user()->willReturn($user);
        $this->beConstructedWith($repository, $auth);
        $this->id->shouldBe('id');
        $this->username->shouldBe('Barfoo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Fan');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_authorises_logged_in_users($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_does_not_authorise_guests($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     */
    function it_checks_whether_or_not_it_has_an_idol($idol, $repository)
    {
        $repository->is_fan_of($this, $idol)->shouldBeCalled()->willReturn(TRUE);
        $this->has_idol($idol)->shouldReturn(TRUE);
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     */
    function it_can_add_new_idols($idol, $repository)
    {
        $repository->add_idol($this, $idol)->shouldBeCalled();
        $this->add_idol($idol);
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     */
    function it_can_remove_idols($idol, $repository)
    {
        $repository->remove_idol($this, $idol)->shouldBeCalled();
        $this->remove_idol($idol);
    }

}
