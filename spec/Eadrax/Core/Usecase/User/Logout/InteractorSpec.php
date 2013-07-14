<?php

namespace spec\Eadrax\Core\Usecase\User\Logout;

use PhpSpec\ObjectBehavior;

class InteractorSpec extends ObjectBehavior
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
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Logout\Interactor');
    }

    function it_runs_the_interaction_chain($authenticator)
    {
        $authenticator->logout()->shouldBeCalled();
        $this->interact();
    }
}
