<?php

namespace spec\Eadrax\Core\Usecase\Hook\Add;

use PHPSpec2\ObjectBehavior;

class Service extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Add\Service');
    }
}
