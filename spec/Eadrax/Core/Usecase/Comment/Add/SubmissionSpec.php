<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubmissionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Submission');
    }
}
