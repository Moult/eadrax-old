<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class TextSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Text $text
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($text, $repository, $authenticator)
    {
        $text->id = 'update_id';
        $text->private = 'update_private';
        $text->message = 'message';
        $this->beConstructedWith($text, $repository, $authenticator);
        $this->load_prepared_proposal($text);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Text');
    }

    function it_is_a_text_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Text');
    }

    function it_is_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_can_submit_and_get_id($repository)
    {
        $repository->save_text('update_id', 'update_private', 'message')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
    }
}
