<?php

namespace spec\Eadrax\Core\Usecase\User\Register;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Register\Registrant $registrant
     * @param Eadrax\Core\Usecase\User\Login\Interactor $user_login
     */
    function let($registrant, $user_login)
    {
        $this->beConstructedWith($registrant, $user_login);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Register\Interactor');
    }

    function it_should_run_the_interaction_chain($registrant, $user_login)
    {
        $registrant->authorise()->shouldBeCalled();
        $registrant->validate()->shouldBeCalled();
        $registrant->register()->shouldBeCalled();
        $user_login->interact()->shouldBeCalled();
        $this->interact();
    }
}
