<?php

namespace spec\Eadrax\Eadrax\Exception;

use PHPSpec2\ObjectBehavior;

class Multiple extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array('foo' => 'bar'));
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Exception\Multiple');
    }

    function it_is_an_exception()
    {
        $this->shouldHaveType('Exception');
    }

    function it_should_return_errors_as_an_array()
    {
        $this->as_array()->shouldBe(array('foo' => 'bar'));
    }

    function it_should_offer_errors_in_the_form_of_a_string()
    {
        $this->getMessage()->shouldBe('Multiple exceptions thrown: foo');
    }
}
