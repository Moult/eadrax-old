<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Hook\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $repository, $authenticator)
    {
        $project->id = 'project_id';
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Project');
    }

    function it_is_a_project_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_authorises_project_authors($authenticator, $author, $repository)
    {
        $author->id = 'author_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($author);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->authorise();
    }

    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $author
     */
    function it_does_not_authorise_users_who_are_not_project_authors($authenticator, $user, $repository)
    {
        $user->id = 'user_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($user);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_removes_services($repository)
    {
        $repository->delete_service_hook('project_id', 'hook_id')->shouldBeCalled();
        $this->remove_service('hook_id');
    }
}
