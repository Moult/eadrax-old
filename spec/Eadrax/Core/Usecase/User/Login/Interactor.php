<?php

namespace spec\Eadrax\Core\Usecase\User\Login;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Login\Guest $guest
     */
    function let($guest)
    {
        $this->beConstructedWith($guest);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Login\Interactor');
    }

    function it_should_run_the_interaction_chain($guest)
    {
        $guest->authorise()->shouldBeCalled();
        $guest->validate()->shouldBeCalled();
        $guest->login()->shouldBeCalled();
        $this->interact();
    }
}
