<?php

namespace spec\Eadrax\Core\Usecase\Project\Untrack;

use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Untrack\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Data\User $fan
     */
    function let($project, $repository, $authenticator, $fan)
    {
        $project->id = 'project_id';
        $fan->id = 'fan_id';
        $authenticator->get_user()->willReturn($fan);
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Untrack\Project');
    }

    function it_should_be_a_projcet()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_can_check_whether_it_has_a_fan($repository)
    {

        $repository->does_project_have_fan('project_id', 'fan_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_fan()->shouldReturn(TRUE);
    }

    function it_gets_the_project_author_id($repository)
    {
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->get_project_author_id()->shouldReturn('author_id');
    }

    function it_can_remove_a_fan($repository)
    {
        $repository->remove_fan_from_project('fan_id', 'project_id')->shouldBeCalled();
        $this->remove_fan();
    }
}
