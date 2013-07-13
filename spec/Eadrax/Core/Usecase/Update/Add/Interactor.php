<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Add\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     */
    function let($project, $proposal)
    {
        $this->beConstructedWith($project, $proposal);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    function it_carries_out_the_generic_interaction_chain($project, $proposal)
    {
        $project->authorise()->shouldBeCalled();
        $proposal->validate()->shouldBeCalled();
        $this->interact();
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Text $text
     */
    function it_carries_out_the_text_submit_process($project, $text)
    {
        $this->beConstructedWith($project, $text);
        $text->submit()->shouldBeCalled();
        $text->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Paste $paste
     */
    function it_carries_out_the_paste_submit_process($project, $paste)
    {
        $this->beConstructedWith($project, $paste);
        $paste->submit()->shouldBeCalled();
        $paste->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }
}
