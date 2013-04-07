<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Edit extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Edit\Repository $user_edit
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($user, $user_edit, $auth, $validation)
    {
        $auth->get_user()->willReturn($user);
        $data = array(
            'user' => $user
        );
        $repositories = array(
            'user_edit' => $user_edit
        );
        $tools = array(
                'auth' => $auth,
                'validation' => $validation
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
