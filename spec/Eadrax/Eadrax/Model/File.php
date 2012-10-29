<?php

namespace spec\Eadrax\Eadrax\Model;

use PHPSpec2\ObjectBehavior;

class File extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\File');
    }
}
