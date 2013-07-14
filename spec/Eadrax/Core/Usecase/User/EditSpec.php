<?php

namespace spec\Eadrax\Core\Usecase\User;

use PhpSpec\ObjectBehavior;

class EditSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Edit\Repository $user_edit
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($user, $user_edit, $authenticator, $validator)
    {
        $authenticator->get_user()->willReturn($user);

        $data = array(
            'user' => $user
        );

        $repositories = array(
            'user_edit' => $user_edit
        );

        $tools = array(
            'authenticator' => $authenticator,
            'validator' => $validator
        );
        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Edit');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Edit\Interactor');
    }
}
