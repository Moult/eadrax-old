<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Track\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Data\User $fan
     */
    function let($project, $repository, $authenticator, $fan)
    {
        $fan->id = 'fan_id';
        $project->id = 'project_id';
        $authenticator->get_user()->willReturn($fan);
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Project');
    }

    function it_is_a_project_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_can_check_whether_it_has_a_fan($repository)
    {
        $repository->does_project_have_fan('project_id', 'fan_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_fan()->shouldReturn(TRUE);
    }

    function it_can_add_a_fan($repository)
    {
        $repository->add_fan_to_project('fan_id', 'project_id')->shouldBeCalled();
        $this->add_fan();
    }

    function it_can_get_id()
    {
        $this->get_id()->shouldReturn('project_id');
    }
}
