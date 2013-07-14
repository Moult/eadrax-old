<?php

namespace spec\Eadrax\Core\Usecase\Project\Untrack;

use PhpSpec\ObjectBehavior;

class FanSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $fan
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Usecase\Project\Untrack\Repository $repository
     */
    function let($fan, $authenticator, $repository)
    {
        $fan->id = 'fan_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($fan);
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

    function it_checks_whether_it_has_an_idol($repository)
    {
        $repository->does_fan_have_idol('fan_id', 'idol_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_idol('idol_id')->shouldReturn(TRUE);
    }

    function it_can_track_all_projects_by_user($repository)
    {
        $repository->get_project_ids_by_author('project_author_id')->shouldBeCalled()->willReturn(array('project_id'));
        $repository->add_fan_to_project('fan_id', 'project_id')->shouldBeCalled();
        $this->track_all_projects_by_user('project_author_id');
    }
}
