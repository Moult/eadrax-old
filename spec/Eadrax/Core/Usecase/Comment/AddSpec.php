<?php

namespace spec\Eadrax\Core\Usecase\Comment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Comment\Add\Repository $comment_add
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($comment, $update, $comment_add, $authenticator, $emailer, $formatter, $validator)
    {
        $comment->update = $update;
        $data = array(
            'comment' => $comment
        );

        $repositories = array(
            'comment_add' => $comment_add
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Interactor');
    }
}
