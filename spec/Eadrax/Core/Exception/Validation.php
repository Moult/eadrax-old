<?php

namespace spec\Eadrax\Core\Exception;

use PHPSpec2\ObjectBehavior;

class Validation extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(array('foo' => 'bar'));
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Exception\Validation');
    }

    function it_can_hold_multiple_exception_errors()
    {
        $this->shouldHaveType('Eadrax\Core\Exception\Multiple');
    }
}
