<?php

namespace spec\Eadrax\Core\Usecase\Comment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Comment $comment
     * @param Eadrax\Core\Usecase\Comment\Edit\Repository $comment_edit
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($comment, $comment_edit, $authenticator, $validator)
    {
        $data = array(
            'comment' => $comment
        );

        $repositories = array(
            'comment_edit' => $comment_edit
        );

        $tools = array(
            'authenticator' => $authenticator,
            'validator' => $validator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Edit');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Comment\Edit\Interactor');
    }
}
