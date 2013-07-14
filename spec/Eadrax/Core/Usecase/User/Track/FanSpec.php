<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PhpSpec\ObjectBehavior;

class FanSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Track\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Data\User $user
     */
    function let($repository, $authenticator, $user)
    {
        $user->id = 'fan_id';
        $authenticator->get_user()->willReturn($user);
        $this->beConstructedWith($repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Fan');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_authorises_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_does_not_authorise_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_checks_whether_or_not_it_has_an_idol($repository)
    {
        $repository->does_fan_have_idol('fan_id', 'idol_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_idol('idol_id')->shouldReturn(TRUE);
    }

    function it_can_add_new_idols($repository)
    {
        $repository->add_idol_to_fan('idol_id', 'fan_id')->shouldBeCalled();
        $this->add_idol('idol_id');
    }

    function it_can_remove_tracked_projects_by_an_author($repository)
    {
        $repository->remove_tracked_projects_authored_by_idol('fan_id', 'idol_id')->shouldBeCalled();
        $this->remove_tracked_projects_by('idol_id');
    }

    function it_can_get_id()
    {
        $this->get_id()->shouldReturn('fan_id');
    }
}
