<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PHPSpec2\ObjectBehavior;

class Fan extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Track\Repository $repository
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Tool\Auth $auth
     */
    function let($repository, $user, $auth)
    {
        $user->id = 'id';
        $user->username = 'Foo';
        $auth->get_user()->willReturn($user);
        $this->beConstructedWith($repository, $auth);
        $this->id->shouldBe('id');
        $this->username->shouldBe('Foo');
    }
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Fan');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_authorise_logged_in_users($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_should_not_authorise_guests($auth)
    {
        $auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     */
    function it_should_check_whether_or_not_it_is_tracking_a_project($project, $repository)
    {
        $repository->is_user_tracking_project($this, $project)->shouldBeCalled()->willReturn(TRUE);
        $this->is_tracking_project($project)->shouldReturn(TRUE);
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     */
    function it_can_remove_tracked_projects($project, $repository)
    {
        $repository->remove_project($this, $project)->shouldBeCalled();
        $this->remove_project($project);
    }

    /**
     * @param Eadrax\Core\Data\User $idol
     */
    function it_checks_whether_or_not_it_has_an_idol($idol, $repository)
    {
        $repository->is_fan_of($this, $idol)->shouldBeCalled()->willReturn(TRUE);
        $this->has_idol($idol)->shouldReturn(TRUE);
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\Idol $idol
     */
    function it_can_remove_idols($idol, $repository)
    {
        $repository->remove_idol($this, $idol)->shouldBeCalled();
        $this->remove_idol($idol);
    }

    /**
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     * @param Eadrax\Core\Data\Project $project2
     */
    function it_tracks_all_projects_by_author_except_for_the_project_itself($author, $project, $project2, $repository)
    {
        $project->author = $author;
        $repository->get_projects_by_author($project->author)->shouldBeCalled()
            ->willReturn(array($project, $project2));
        $repository->add_projects($this, array($project2))->shouldBeCalled();
        $this->track_all_projects_by_author_except_for($project);
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_checks_if_is_a_fan_of_all_projects_by_project_author($author, $repository)
    {
        $repository->number_of_projects_by($author)->shouldBeCalled()->willReturn(3);
        $repository->number_of_projects_tracked_by($this, $author)->shouldBeCalled()->willReturn(2);
        $this->is_fan_of_all_other_projects_by($author)->shouldReturn(TRUE);
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_can_untrack_all_projects_by_an_author($author, $repository)
    {
        $repository->remove_projects_by_author($this, $author)->shouldBeCalled();
        $this->untrack_all_projects_by($author);
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     */
    function it_can_track_a_project($project, $repository)
    {
        $repository->add_project($this, $project)->shouldBeCalled();
        $this->add_project($project);
    }
}
