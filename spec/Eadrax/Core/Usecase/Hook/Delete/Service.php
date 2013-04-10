<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PHPSpec2\ObjectBehavior;

class Service extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Hook $hook
     */
    function let($hook)
    {
        $hook->id = 'id';
        $this->beConstructedWith($hook);
        $this->id->shouldBe('id');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Service');
    }

    function it_is_a_hook()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Hook');
    }
}
