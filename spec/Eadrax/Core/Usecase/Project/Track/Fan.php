<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PHPSpec2\ObjectBehavior;

class Fan extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Fan');
    }
}
