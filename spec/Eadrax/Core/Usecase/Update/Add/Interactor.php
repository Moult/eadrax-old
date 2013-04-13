<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Add\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     */
    function let($project, $proposal)
    {
        $this->beConstructedWith($project, $proposal);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Interactor');
    }

    function it_carries_out_the_usecase($project, $proposal)
    {
        $project->authorise()->shouldBeCalled();
        $proposal->validate()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $project->notify_trackers($proposal)->shouldBeCalled();
        $this->interact();
    }

    function it_uploads_a_file($proposal)
    {
        $proposal->type = 'file';
        $proposal->upload->shouldBeCalled();
        $this->interact();
    }

    function it_creates_thumbnails_for_websites($proposal)
    {
        $proposal->type = 'website';
        $proposal->generate_thumbnail()->shouldBeCalled();
        $this->interact();
    }

    function it_creates_thumbnails_for_image_files($proposal)
    {
        $proposal->type = 'file';
        $proposal->extra = 'image';
        $proposal->generate_thumbnail()->shouldBeCalled();
        $this->interact();
    }

    function it_generates_thumbnails_for_videos($proposal)
    {
        $proposal->type = 'file';
        $proposal->extra = 'video';
        $proposal->generate_thumbnail()->shouldBeCalled();
        $this->interact();
    }

    function it_generates_thumbnails_for_sounds($proposal)
    {
        $proposal->type = 'file';
        $proposal->extra = 'sound';
        $proposal->generate_thumbnail()->shouldBeCalled();
        $this->interact();
    }

    function it_generates_metadata_for_files($proposal)
    {
        $proposal->type = 'file';
        $proposal->generate_metadata()->shouldBeCalled();
        $this->interact();
    }

    function it_does_not_notify_trackers_if_proposal_is_private($project, $proposal)
    {
        $proposal->private = TRUE;
        $project->notify_trackers($proposal)->shouldNotBeCalled();
        $this->interact();
    }
}
