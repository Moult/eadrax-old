<?php

namespace spec\Eadrax\Core\Usecase\Project\Delete;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $repository, $authenticator)
    {
        $project->id = 'project_id';
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\Proposal');
    }

    function it_is_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_authorises_valid_project_authors($author, $authenticator, $repository)
    {
        $author->id = 1;
        $authenticator->get_user()->shouldBeCalled()->willReturn($author);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn($author);
        $this->shouldNotThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Data\User $impostor
     */
    function it_does_not_verify_ownership_with_other_users($author, $impostor, $repository, $authenticator)
    {
        $author->id = 1;
        $impostor->id = 1;
        $authenticator->get_user()->shouldBeCalled()->willReturn($impostor);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn($author);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_deletes_the_project($repository)
    {
        $repository->delete('project_id')->shouldBeCalled();
        $this->delete();
    }
}
