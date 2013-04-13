<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validation $validation
     */
    public function let($update, $project, $validation)
    {
        $update->type = 'type';
        $update->content = 'content';
        $update->extra = 'extra';
        $update->private = 'private';
        $update->project = $project;
        $this->beConstructedWith($update, $validation);
    }

    function it_should_be_initializable($project)
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
        $this->type->shouldBe('type');
        $this->content->shouldBe('content');
        $this->extra->shouldBe('extra');
        $this->private->shouldBe('private');
        $this->project->shouldBe($project);
    }

    function it_should_be_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_validates_text_updates($update, $validation)
    {
        $update->type = 'text';
        $update->content = 'Foobar';
        $validation->setup(array('content' => 'Foobar'))->shouldBeCalled();
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
        $this->extra->shouldBe(NULL);
    }

    function it_validates_paste_updates($update, $validation)
    {
        $update->type = 'paste';
        $update->extra = 'bash';
        $update->content = '#! /bin/bash';
        $validation->setup(array(
            'content' => '#! /bin/bash',
            'syntax' => 'bash'
        ))->shouldBeCalled();
        $validation->rule('content', 'not_empty')->shouldBeCalled();
        $validation->rule('syntax', 'not_empty')->shouldBeCalled();
        $validation->callback('syntax', array($this, 'validate_paste_syntax'), array('syntax'))->shouldBeCalled();
        $validation->check()->shouldBeCalled()->willReturn(FALSE);
        $validation->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_validates_paste_syntax()
    {
        $this->validate_paste_syntax('bash')->shouldReturn(TRUE);
        $this->validate_paste_syntax('english')->shouldReturn(FALSE);
    }
}
