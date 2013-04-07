<?php

namespace spec\Eadrax\Core\Usecase\User\Edit;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Edit\User $user
     */
    function let($user)
    {
        $this->beConstructedWith($user);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Edit\Interactor');
    }

    function it_should_run_the_interaction_chain($user)
    {
        $user->authorise()->shouldBeCalled();
        $user->validate()->shouldBeCalled();
        $user->update()->shouldBeCalled();
        $this->interact();
    }
}
