<?php

namespace spec\Eadrax\Core\Usecase\User;

use PHPSpec2\ObjectBehavior;

class Logout extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($authenticator)
    {
        $this->beConstructedWith($authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Logout');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Logout\Interactor');
    }
}
