<?php

namespace spec\Eadrax\Core\Usecase\Comment\Edit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubmissionSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Usecase\Comment\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($comment, $repository, $authenticator, $validator)
    {
        $comment->id = 'id';
        $comment->text = 'text';
        $this->beConstructedWith($comment, $repository, $authenticator, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Edit\Submission');
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

    function it_validates_the_comment($validator)
    {
        $validator->setup(array(
            'text' => 'text'
        ))->shouldBeCalled();
        $validator->rule('text', 'not_empty')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array('text'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_can_update_the_comment($repository)
    {
        $repository->update_comment('id', 'text')->shouldBeCalled();
        $this->update();
    }
}
