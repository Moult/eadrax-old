<?php

namespace spec\Eadrax\Eadrax\Context\User;

require_once 'spec/Eadrax/Eadrax/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context\Core;

class Logout extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Eadrax\Entity\Auth $auth
     */
   function let($auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Logout');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Core');
    }

    function it_should_return_success($auth)
    {
        $auth->logout()->shouldBeCalled();
        $this->execute()->shouldBe(array(
            'status' => 'success'
        ));
    }
}
