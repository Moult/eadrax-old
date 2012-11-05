<?php

namespace spec\Eadrax\Eadrax\Data;

require_once 'spec/Eadrax/Eadrax/Data/Core.php';

use PHPSpec2\ObjectBehavior;

class File extends ObjectBehavior
{
    use Core;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Data\File');
    }
}
