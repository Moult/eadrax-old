<?php

namespace spec\Eadrax\Core\Context\User;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Logout extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Entity\Auth $auth
     */
    function let($auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Logout');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Core');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Context\User\Logout\Interactor');
    }
}
