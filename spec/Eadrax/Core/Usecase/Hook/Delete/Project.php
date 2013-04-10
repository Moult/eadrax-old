<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Project');
    }
}
