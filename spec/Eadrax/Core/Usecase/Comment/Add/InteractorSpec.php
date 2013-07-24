<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Comment\Add\Update $update
     * @param Eadrax\Core\Usecase\Comment\Add\Submission $submission
     */
    function let($update, $submission)
    {
        $this->beConstructedWith($submission, $update);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Interactor');
    }

    function it_carries_out_the_interaction_chain($update, $submission)
    {
        $update->does_exist()->shouldBeCalled()->willReturn(TRUE);
        $submission->authorise()->shouldBeCalled();
        $submission->validate()->shouldBeCalled();
        $submission->submit()->shouldBeCalled();
        $submission->get_author_username()->shouldBeCalled()->willReturn('comment_author_username');
        $submission->get_text()->shouldBeCalled()->willReturn('comment_text');
        $update->notify_author_about_comment('comment_author_username', 'comment_text')->shouldBeCalled();
        $this->interact();
    }
}
