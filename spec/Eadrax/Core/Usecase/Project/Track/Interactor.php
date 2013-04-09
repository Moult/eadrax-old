<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Track\Fan $fan
     * @param Eadrax\Core\Usecase\Project\Track\Project $project
     * @param Eadrax\Core\Usecase\User\Track\Interactor $user_track
     * @param Eadrax\Core\Data\User $author
     */
    function let($fan, $project, $user_track, $author)
    {
        $project->author = $author;
        $this->beConstructedWith($fan, $project, $user_track);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Interactor');
    }

    function it_authorises_the_fan_before_anything_happens($fan)
    {
        $fan->authorise()->shouldBeCalled();
        $this->interact();
    }

    function it_untracks_the_project_if_the_user_is_already_tracking_it($fan, $project)
    {
        $fan->is_tracking_project($project)->shouldBeCalled()->willReturn(TRUE);
        $fan->remove_project($project)->shouldBeCalled();
        $this->interact();
    }

    function it_untracks_the_idol_if_the_fan_is_already_tracking_it($fan, $author, $project)
    {
        $fan->is_tracking_project($project)->willReturn(FALSE);
        $fan->has_idol($author)->shouldBeCalled()->willReturn(TRUE);
        $fan->remove_idol($author)->shouldBeCalled();
        $fan->track_all_projects_by_author_except_for($project)->shouldBeCalled();
        $this->interact();
    }

    function it_has_fans_track_idols_if_they_are_already_tracking_all_projects($fan, $project, $author, $user_track)
    {
        $fan->is_tracking_project($project)->shouldBeCalled()->willReturn(FALSE);
        $fan->has_idol($author)->shouldBeCalled()->willReturn(FALSE);
        $fan->is_fan_of_all_other_projects_by_($author)->shouldBeCalled()->willReturn(TRUE);
        $fan->untrack_all_projects_by($author)->shouldBeCalled();
        $user_track->interact()->shouldBeCalled();
        $this->interact();
    }

    function it_allows_users_to_track_projects($fan, $project, $author)
    {
        $fan->has_idol($author)->shouldBeCalled()->willReturn(FALSE);
        $fan->is_tracking_project($project)->shouldBeCalled()->willReturn(FALSE);
        $fan->is_fan_of_all_other_projects_by_($author)->shouldBeCalled()->willReturn(FALSE);
        $fan->add_project($project)->shouldBeCalled();
        $project->notify_author($fan)->shouldBeCalled();
        $this->interact();
    }
}
