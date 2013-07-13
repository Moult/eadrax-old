<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Paste extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Paste $paste
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($paste, $project, $repository, $validator)
    {
        $project->id = 'project_id';
        $paste->project = $project;
        $paste->private = 'update_private';
        $paste->text = 'text';
        $paste->syntax = 'syntax';
        $this->beConstructedWith($paste, $repository, $validator);
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

    function it_validates_paste_updates($validator)
    {
        $validator->setup(array(
            'text' => 'text',
            'syntax' => 'syntax'
        ))->shouldBeCalled();
        $validator->rule('content', 'not_empty')->shouldBeCalled();
        $validator->rule('syntax', 'not_empty')->shouldBeCalled();
        $validator->callback('syntax', array($this, 'validate_syntax'), array('syntax'))->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_validates_syntax()
    {
        $this->validate_syntax('bash')->shouldReturn(TRUE);
        $this->validate_syntax('english')->shouldReturn(FALSE);
    }

    function it_can_submit_and_get_id($repository)
    {
        $repository->save_paste('project_id', 'update_private', 'text', 'syntax')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
