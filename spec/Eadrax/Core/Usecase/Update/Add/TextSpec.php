<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($text, $project, $repository, $validator)
    {
        $project->id = 'project_id';
        $text->project = $project;
        $text->private = 'update_private';
        $text->message = 'message';
        $this->beConstructedWith($text, $repository, $validator);
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

    function it_can_validate($validator)
    {
        $validator->setup(array('message' => 'message'))->shouldBeCalled();
        $validator->rule('message', 'not_empty')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array('message'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_can_submit_and_get_id($repository)
    {
        $repository->save_text('project_id', 'update_private', 'message')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
