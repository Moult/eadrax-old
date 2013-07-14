<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PhpSpec\ObjectBehavior;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Track\Author $author
     * @param Eadrax\Core\Usecase\Project\Track\Fan $fan
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     * @param Eadrax\Core\Usecase\Project\Track\User\Track $user_track
     */
    function let($author, $fan, $project, $user_track)
    {
        $this->beConstructedWith($author, $fan, $project, $user_track);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Interactor');
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\Interactor $user_track_interactor
     */
    function it_adds_an_idol_if_user_is_a_fan_of_all_projects($author, $fan, $project, $user_track, $user_track_interactor)
    {
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $author->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $author->get_id()->shouldBeCalled()->willReturn('author_id');
        $author->get_number_of_projects()->shouldBeCalled()->willReturn(10);
        $fan->get_number_of_projects_owned_by_author('author_id')->shouldBeCalled()->willReturn(9);
        $author->remove_fan_from_all_projects()->shouldBeCalled();
        $user_track->set_author_id('author_id')->shouldBeCalled();
        $user_track->fetch()->shouldBeCalled()->willReturn($user_track_interactor);
        $user_track_interactor->interact()->shouldBeCalled();
        $project->add_fan()->shouldNotBeCalled();
        $this->interact();
    }

    function it_adds_a_fan_normally_otherwise($fan, $author, $project)
    {
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $author->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $author->get_id()->shouldBeCalled()->willReturn('author_id');
        $author->get_number_of_projects()->shouldBeCalled()->willReturn(10);
        $fan->get_number_of_projects_owned_by_author('author_id')->shouldBeCalled()->willReturn(1);
        $project->add_fan()->shouldBeCalled();
        $project->get_id()->shouldBeCalled()->willReturn('project_id');
        $author->notify_about_new_project_tracker('project_id')->shouldBeCalled();
        $this->interact();
    }

    function it_does_nothing_if_project_already_has_user_as_a_fan($fan, $project, $user_track)
    {
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(TRUE);
        $user_track->fetch()->shouldNotBeCalled();
        $project->add_fan()->shouldNotBeCalled();
        $this->interact();
    }

    function it_does_nothing_if_project_author_already_has_user_as_a_fan($author, $fan, $project, $user_track)
    {
        $fan->authorise()->shouldBeCalled();
        $project->has_fan()->shouldBeCalled()->willReturn(FALSE);
        $author->has_fan()->shouldBeCalled()->willReturn(TRUE);
        $user_track->fetch()->shouldNotBeCalled();
        $project->add_fan()->shouldNotBeCalled();
        $this->interact();
    }
}
