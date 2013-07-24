<?php

namespace spec\Eadrax\Core\Usecase\Comment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeleteSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Usecase\Comment\Delete\Repository $comment_delete
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($comment, $comment_delete, $authenticator)
    {
        $data = array(
            'comment' => $comment
        );

        $repositories = array(
            'comment_delete' => $comment_delete
        );

        $tools = array(
            'authenticator' => $authenticator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Comment\Delete\Interactor');
    }
}
