<?php

namespace spec\Eadrax\Core\Usecase\User\Register;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\User\Register\Guest $guest
     * @param Eadrax\Core\Usecase\User\Login\Interactor $user_login
     */
    function let($guest, $user_login)
    {
        $this->beConstructedWith($guest, $user_login);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Register\Interactor');
    }

    function it_should_run_the_interaction_chain($guest, $user_login)
    {
        $guest->authorise_registration()->shouldBeCalled();
        $guest->validate_information()->shouldBeCalled();
        $guest->register()->shouldBeCalled();
        $user_login->interact()->shouldBeCalled();
        $this->interact();
    }

    function it_catches_authorisation_exceptions($guest)
    {
        $guest->authorise_registration()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_catches_validation_exceptions($guest)
    {
        $guest->validate_information()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Validation', array('foo' => 'bar'));
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo' => 'bar')
            )
        ));
    }

    function it_executes_the_usecase_successfully()
    {
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
