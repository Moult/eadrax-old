<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Usecase\Project\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $author, $repository, $authenticator)
    {
        $project->name = 'project_name';
        $project->summary = 'project_summary';
        $author->id = 'author_id';

        $authenticator->get_user()->willReturn($author);

        $this->beConstructedWith($project, $repository, $authenticator);
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
        $repository->add(
            'project_name',
            'project_summary',
            'author_id')
            ->shouldBeCalled()->willReturn(42);
        $this->submit();
        $this->id->shouldBe(42);
    }
}
