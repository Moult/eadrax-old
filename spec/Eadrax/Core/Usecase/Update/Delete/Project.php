<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Project');
    }
}
