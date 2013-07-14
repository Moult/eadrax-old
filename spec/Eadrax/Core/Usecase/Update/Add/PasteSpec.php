<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class PasteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Paste $paste
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($paste, $project, $repository)
    {
        $project->id = 'project_id';
        $paste->project = $project;
        $paste->private = 'update_private';
        $paste->text = 'text';
        $paste->syntax = 'syntax';
        $this->beConstructedWith($paste, $repository);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Paste');
    }

    function it_should_be_a_paste()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Paste');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_can_submit_and_get_id($repository)
    {
        $repository->save_paste('project_id', 'update_private', 'text', 'syntax')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
