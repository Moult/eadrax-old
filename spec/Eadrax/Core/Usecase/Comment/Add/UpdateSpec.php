<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UpdateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Update');
    }
}
