<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\User');
    }
}
