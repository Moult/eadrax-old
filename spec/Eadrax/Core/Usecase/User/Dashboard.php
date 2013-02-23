<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Dashboard extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Tool\Auth $tool_auth
     */
    function let($data_user, $tool_auth)
    {
        $this->beConstructedWith($data_user, $tool_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Dashboard');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Dashboard\Interactor');
    }
}
