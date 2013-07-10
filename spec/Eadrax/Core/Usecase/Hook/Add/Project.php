<?php

namespace spec\Eadrax\Core\Usecase\Hook\Add;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Hook\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $repository, $authenticator)
    {
        $project->id = 'project_id';
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Add\Project');
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
     */
    function it_does_not_authorise_users_who_are_not_project_authors($authenticator, $user, $repository)
    {
        $user->id = 'user_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($user);
        $repository->get_project_author_id('project_id')->shouldBeCalled()->willReturn('author_id');
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Usecase\Hook\Add\Service $service
     */
    function it_can_check_if_it_already_has_a_service($service, $repository)
    {
        $service->url = 'service_url';
        $repository->has_existing_service('project_id', 'service_url')->shouldBeCalled()->willReturn(TRUE);
        $this->has_service($service)->shouldReturn(TRUE);
    }

    /**
     * @param Eadrax\Core\Usecase\Hook\Add\Service $service
     */
    function it_can_add_new_service_hooks($service, $repository)
    {
        $service->url = 'service_url';
        $repository->add_service_hook('project_id', 'service_url')->shouldBeCalled();
        $this->add_service($service);
    }
}
