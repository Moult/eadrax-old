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
        $guest->authorise_login()->shouldBeCalled();
        $guest->validate_information()->shouldBeCalled();
        $guest->login()->shouldBeCalled();
        $this->interact();
    }

    function it_should_catch_authorisation_exceptions($guest)
    {
        $guest->authorise_login()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_should_catch_validation_exceptions($guest)
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

    function it_should_execute_the_usecase_successfully()
    {
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
