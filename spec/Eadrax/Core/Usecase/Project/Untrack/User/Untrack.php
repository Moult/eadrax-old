<?php

namespace spec\Eadrax\Core\Usecase\Project\Untrack\User;

use PHPSpec2\ObjectBehavior;

class Untrack extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Untrack\Repository $user_untrack
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($user, $user_untrack, $authenticator)
    {
        $data = array(
            'user' => $user
        );

        $repositories = array(
            'user_untrack' => $user_untrack
        );

        $tools = array(
            'authenticator' => $authenticator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Untrack\User\Untrack');
    }

    function it_fetches_the_user_untrack_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Untrack\Interactor');
    }

    function it_can_set_the_author_id()
    {
        $this->set_author_id('author_id');
        $this->data['user']->id->shouldBe('author_id');
    }
}
