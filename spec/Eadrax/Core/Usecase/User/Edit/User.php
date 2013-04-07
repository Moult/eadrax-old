<?php

namespace spec\Eadrax\Core\Usecase\User\Edit;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Edit\User');
    }
}
