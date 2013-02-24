<?php

namespace spec\Eadrax\Core\Usecase\User\Logout;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Logout\Interactor');
    }

    function it_runs_the_interaction_chain($auth)
    {
        $auth->logout()->shouldBeCalled();
        $this->interact();
    }
}
