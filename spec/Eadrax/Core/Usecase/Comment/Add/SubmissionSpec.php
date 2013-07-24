<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubmissionSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\User $logged_in_user
     * @param Eadrax\Core\Usecase\Comment\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($comment, $update, $logged_in_user, $repository, $authenticator, $validator)
    {
        $update->id = 'update_id';
        $logged_in_user->id = 'author_id';
        $authenticator->get_user()->willReturn($logged_in_user);
        $comment->text = 'text';
        $comment->update = $update;
        $this->beConstructedWith($comment, $repository, $authenticator, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Submission');
    }

    function it_should_be_a_comment()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Comment');
    }

    function it_authorises_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_should_validate($validator)
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

    function it_should_submit($repository)
    {
        $repository->add_comment('text', 'author_id', 'update_id')->shouldBeCalled();
        $this->submit();
    }
}
