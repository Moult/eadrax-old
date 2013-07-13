<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $repository, $authenticator)
    {
        $project->id = 'project_id';
        $repository->get_project_name_and_author_id_and_username('project_id')->willReturn(array('project_name', 'author_id', 'author_username'));
        $this->beConstructedWith($project, $repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Project');
    }

    function it_should_be_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_authorises_project_authors($authenticator, $logged_in_user)
    {
        $logged_in_user->id = 'author_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->authorise_ownership();
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_does_not_authorise_impostors($authenticator, $logged_in_user)
    {
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise_ownership();
    }
}
