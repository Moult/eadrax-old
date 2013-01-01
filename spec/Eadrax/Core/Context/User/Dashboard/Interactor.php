<?php

namespace spec\Eadrax\Core\Context\User\Dashboard;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Context\User\Dashboard\User $user
     */
    function let($user)
    {
        $this->beConstructedWith($user);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Dashboard\Interactor');
    }

    function it_should_run_the_interaction_chain($user)
    {
        $user->authorise_user_dashboard()->shouldBeCalled();
        $this->interact();
    }

    function it_should_catch_authorisation_exceptions($user)
    {
        $user->authorise_user_dashboard()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_successfully($user)
    {
        $user->authorise_user_dashboard()->shouldBeCalled()->willReturn(array('foo' => 'bar'));
        $this->execute()->shouldReturn(array(
            'status' => 'success',
            'data' => array('foo' => 'bar')
        ));
    }
}
