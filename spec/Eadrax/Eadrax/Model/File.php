<?php

namespace spec\Eadrax\Eadrax\Model;

require_once 'spec/Eadrax/Eadrax/Model/Core.php';

use PHPSpec2\ObjectBehavior;

class File extends ObjectBehavior
{
    use Core;

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\File');
    }
}
