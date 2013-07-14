<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($text, $project, $validator)
    {
        $project->id = 'project_id';
        $text->project = $project;
        $text->private = 'update_private';
        $text->message = 'message';
        $this->beConstructedWith($text, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Text');
    }

    function it_is_a_text_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Text');
    }

    function it_is_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Proposal');
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
}
