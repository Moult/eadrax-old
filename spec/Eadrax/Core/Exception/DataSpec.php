<?php

namespace spec\Eadrax\Core\Exception;

use PhpSpec\ObjectBehavior;

class DataSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Exception\Data');
    }

    function it_it_an_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
