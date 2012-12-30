<?php

namespace spec\Eadrax\Core\Exception;

use PHPSpec2\ObjectBehavior;

class Multiple extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array('foo' => 'bar'));
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Exception\Multiple');
    }

    function it_is_an_exception()
    {
        $this->shouldHaveType('Exception');
    }

    function it_should_return_errors_as_an_array()
    {
        $this->get_errors()->shouldBe(array('foo' => 'bar'));
    }
}
