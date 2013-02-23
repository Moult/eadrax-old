<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Register extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Usecase\User\Register\Repository $repository
     * @param Eadrax\Core\Usecase\User\Login\Repository $repository_user_login
     * @param Eadrax\Core\Tool\Auth $tool_auth
     * @param Eadrax\Core\Tool\Validation $tool_validation
     */
    function let($data_user, $repository, $repository_user_login, $tool_auth, $tool_validation)
    {
        $this->beConstructedWith($data_user, $repository, $repository_user_login, $tool_auth, $tool_validation);
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
