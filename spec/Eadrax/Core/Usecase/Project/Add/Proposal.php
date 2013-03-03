<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\Project\Add\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($project, $user, $repository, $auth)
    {
        $auth->get_user()->willReturn($user);
        $project->name = 'Project name';
        $project->summary = 'Project summary';

        $this->beConstructedWith($project, $repository, $auth);

        $this->name->shouldBe('Project name');
        $this->summary->shouldBe('Project summary');
        $this->author->shouldBe($user);
        $this->views->shouldBe(0);
        $this->last_updated->shouldBe(time());
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
