<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Add\Repository $repository
     */
    function let($project, $repository)
    {
        $project->name = 'foo';
        $this->beConstructedWith($project, $repository);
        $this->name->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_submits_the_proposal_to_the_repository($repository)
    {
        $repository->add($this)->shouldBeCalled()->willReturn(42);
        $this->submit();
        $this->id->shouldBe(42);
    }
}
