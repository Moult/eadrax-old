<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Register extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Register\Repository $user_register
     * @param Eadrax\Core\Usecase\User\Login\Repository $user_login
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($user, $user_register, $user_login, $auth, $validation)
    {
        $data = array(
            'user' => $user
        );
        $repositories = array(
            'user_register' => $user_register,
            'user_login' => $user_login
        );
        $tools = array(
            'auth' => $auth,
            'validation' => $validation
        );
        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Register');
    }

    function it_should_fetch_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Register\Interactor');
    }
}
