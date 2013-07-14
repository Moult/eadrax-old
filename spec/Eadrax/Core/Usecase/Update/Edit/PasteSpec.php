<?php

namespace spec\Eadrax\Core\Usecase\Update\Edit;

use PhpSpec\ObjectBehavior;

class PasteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Paste $paste
     * @param Eadrax\Core\Usecase\Update\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($paste, $repository, $authenticator)
    {
        $paste->id = 'update_id';
        $paste->private = 'update_private';
        $paste->text = 'text';
        $paste->syntax = 'syntax';
        $repository->get_author_id('update_id')->shouldBeCalled()->willReturn('project_author_id');
        $this->beConstructedWith($paste, $repository, $authenticator);
        $this->load_prepared_proposal($paste);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Paste');
    }

    function it_should_be_a_paste()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Paste');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Edit\Proposal');
    }

    function it_can_submit($repository)
    {
        $repository->save_paste('update_id', 'update_private', 'text', 'syntax')->shouldBeCalled()->willReturn('update_id');
        $this->submit();
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_authorises_ownership($logged_in_user, $authenticator)
    {
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise_ownership();
    }
}
