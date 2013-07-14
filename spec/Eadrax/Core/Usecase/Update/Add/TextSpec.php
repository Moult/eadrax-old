<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($text, $project, $repository)
    {
        $project->id = 'project_id';
        $text->project = $project;
        $text->private = 'update_private';
        $text->message = 'message';
        $this->beConstructedWith($repository);
        $this->load_prepared_proposal($text);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Text');
    }

    function it_is_a_text_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Text');
    }

    function it_is_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_can_submit_and_get_id($repository)
    {
        $repository->save_text('project_id', 'update_private', 'message')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
