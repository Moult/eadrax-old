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

    function it_carries_out_the_generic_interaction_chain($project, $proposal)
    {
        $project->authorise()->shouldBeCalled();
        $proposal->validate()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $proposal->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Text $text
     */
    function it_carries_out_the_text_submit_process($project, $text)
    {
        $this->beConstructedWith($project, $text);
        $project->authorise()->shouldBeCalled();
        $text->validate()->shouldBeCalled();
        $text->submit()->shouldBeCalled();
        $text->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Paste $paste
     */
    function it_carries_out_the_paste_submit_process($project, $paste)
    {
        $this->beConstructedWith($project, $paste);
        $project->authorise()->shouldBeCalled();
        $paste->validate()->shouldBeCalled();
        $paste->submit()->shouldBeCalled();
        $paste->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Image $image
     */
    function it_carries_out_the_image_submit_process($project, $image)
    {
        $this->beConstructedWith($project, $image);
        $project->authorise()->shouldBeCalled();
        $image->validate()->shouldBeCalled();
        $image->generate_thumbnail()->shouldBeCalled();
        $image->calculate_dimensions()->shouldBeCalled();
        $image->submit()->shouldBeCalled();
        $image->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Sound $sound
     */
    function it_carries_out_the_sound_submit_process($project, $sound)
    {
        $this->beConstructedWith($project, $sound);
        $project->authorise()->shouldBeCalled();
        $sound->validate()->shouldBeCalled();
        $sound->generate_thumbnail()->shouldBeCalled();
        $sound->calculate_length()->shouldBeCalled();
        $sound->calculate_filesize()->shouldBeCalled();
        $sound->submit()->shouldBeCalled();
        $sound->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Add\Video $video
     */
    function it_carries_out_the_video_submit_process($project, $video)
    {
        $this->beConstructedWith($project, $video);
        $project->authorise()->shouldBeCalled();
        $video->validate()->shouldBeCalled();
        $video->encode_to_webm()->shouldBeCalled();
        $video->generate_thumbnail()->shouldBeCalled();
        $video->calculate_length()->shouldBeCalled();
        $video->calculate_filesize()->shouldBeCalled();
        $video->calculate_dimensions()->shouldBeCalled();
        $video->submit()->shouldBeCalled();
        $video->get_id()->shouldBeCalled()->willReturn('update_id');
        $this->interact()->shouldReturn('update_id');
    }
}
