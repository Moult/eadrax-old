<?php

namespace spec\Eadrax\Core\Usecase\Comment\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubmissionSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Usecase\Comment\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($comment, $repository, $authenticator)
    {
        $comment->id = 'id';
        $this->beConstructedWith($comment, $repository, $authenticator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Delete\Submission');
    }

    function it_is_a_comment()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Comment');
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_authorises_the_comment_author($logged_in_user, $repository, $authenticator)
    {
        $repository->get_comment_author_id('id')->shouldBeCalled()->willreturn('comment_author_id');
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_can_delete_the_comment($repository)
    {
        $repository->delete_comment('id')->shouldBeCalled();
        $this->delete();
    }
}
