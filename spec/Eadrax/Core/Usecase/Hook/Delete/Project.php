<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Hook\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($project, $repository, $auth)
    {
        $project->id = 'id';
        $this->beConstructedWith($project, $repository, $auth);
        $this->id->shouldBe('id');
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
    function it_authorises_project_authors($auth, $author, $repository)
    {
        $auth->get_user()->shouldBeCalled()->willReturn($author);
        $repository->get_project_author($this)->shouldBeCalled()->willReturn($author);
        $this->authorise();
    }

    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $author
     */
    function it_does_not_authorise_users_who_are_not_project_authors($auth, $user, $author, $repository)
    {
        $user->id = '1';
        $author->id = '2';
        $auth->get_user()->shouldBeCalled()->willReturn($user);
        $repository->get_project_author($this)->shouldBeCalled()->willReturn($author);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Usecase\Hook\Delete\Service $service
     */
    function it_removes_services($service, $repository)
    {
        $repository->delete_service_hook_from_project($this, $service)->shouldBeCalled();
        $this->remove_service($service);
    }
}
