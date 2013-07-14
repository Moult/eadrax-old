<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PhpSpec\ObjectBehavior;

class ProposalSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $repository, $authenticator)
    {
        $project->id = 'project_id';
        $project->name = 'project_name';
        $project->summary = 'project_summary';
        $project->description = 'project_description';
        $project->website = 'project_website';

        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Proposal');
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $impostor
     */
    function it_does_not_authorise_users_who_do_not_own_the_project($impostor, $repository, $authenticator)
    {
        $impostor->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($impostor);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_authorises_users_who_own_the_project($author, $repository, $authenticator)
    {
        $author->id = 'author_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($author);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_should_be_able_to_update_the_existing_project($repository)
    {
        $repository->update(
            'project_id',
            'project_name',
            'project_summary',
            'project_description',
            'project_website',
            time()
        )->shouldBeCalled();
        $this->update();
    }
}
