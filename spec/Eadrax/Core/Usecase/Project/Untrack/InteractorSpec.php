<?php

namespace spec\Eadrax\Core\Usecase\Project\Untrack;

use PhpSpec\ObjectBehavior;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Untrack\Fan $fan
     * @param Eadrax\Core\Usecase\Project\Untrack\Project $project
     * @param Eadrax\Core\Usecase\Project\Untrack\User\Untrack $user_untrack
     */
    function let($fan, $project, $user_untrack)
    {
        $this->beConstructedWith($fan, $project, $user_untrack);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Untrack\Interactor');
    }

    function it_authorises_the_fan_before_anything_happens($fan)
    {
        $fan->authorise()->shouldBeCalled();
        $this->interact();
    }

    function it_makes_the_fan_stop_tracking_the_project($fan, $project)
    {
        $project->get_project_author_id()->shouldBeCalled()->willReturn('author_id');
        $fan->has_idol('author_id')->shouldBeCalled()->willReturn(FALSE);
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(TRUE);
        $project->remove_fan()->shouldBeCalled();
        $this->interact();
    }

    /**
     * @param Eadrax\Core\Usecase\User\Untrack\Interactor $user_untrack_interactor
     */
    function it_removes_the_idolship_if_the_fan_idolises_the_project_author($fan, $project, $user_untrack, $user_untrack_interactor)
    {
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(TRUE);
        $project->get_project_author_id()->shouldBeCalled()->willReturn('author_id');
        $fan->has_idol('author_id')->shouldBeCalled()->willReturn(TRUE);
        $user_untrack->set_author_id('author_id')->shouldBeCalled();
        $user_untrack->fetch()->shouldBeCalled()->willReturn($user_untrack_interactor);
        $user_untrack_interactor->interact()->shouldBeCalled();
        $fan->track_all_projects_by_user('author_id')->shouldBeCalled();
        $project->remove_fan()->shouldBeCalled();
        $this->interact();
    }

    function it_does_nothing_if_already_untracked($project)
    {
        $project->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $project->remove_fan()->shouldNotBeCalled();
        $this->interact();
    }
}
