<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Login extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Login\Repository $user_login
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($user_login, $auth, $validation)
    {
        $data = array(
            'username' => 'Moult',
            'password' => 'password'
        );
        $repositories = array(
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
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Login');
    }

    function it_should_fetch_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Login\Interactor');
    }
}
