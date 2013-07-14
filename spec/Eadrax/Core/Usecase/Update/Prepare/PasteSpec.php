<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;

class PasteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Paste $paste
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($paste, $project, $validator)
    {
        $project->id = 'project_id';
        $paste->project = $project;
        $paste->private = 'update_private';
        $paste->text = 'text';
        $paste->syntax = 'syntax';
        $this->beConstructedWith($paste, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Paste');
    }

    function it_should_be_a_paste()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Paste');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Proposal');
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
}
