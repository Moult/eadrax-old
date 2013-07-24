<?php

namespace spec\Eadrax\Core\Usecase\Comment\Add;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UpdateSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Comment\Add\Repository $repository
     */
    function let($update, $repository)
    {
        $update->id = 'id';
        $this->beConstructedWith($update, $repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Comment\Add\Update');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_checks_whether_or_not_the_update_exists($repository)
    {
        $repository->does_update_exist('id')->shouldBeCalled()->willReturn(TRUE);
        $this->does_exist()->shouldReturn(TRUE);
    }
}
