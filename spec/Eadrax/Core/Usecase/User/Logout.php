<?php

namespace spec\Eadrax\Core\Usecase\User;

require_once 'spec/Eadrax/Core/Usecase/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase\Core;

class Logout extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Logout');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Core');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Logout\Interactor');
    }
}
