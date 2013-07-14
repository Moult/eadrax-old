<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InteractorSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Proposal $proposal
     */
    function let($proposal)
    {
        $this->beConstructedWith($proposal);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Interactor');
    }

    function it_carries_out_the_generic_interaction_chain($proposal)
    {
        $proposal->validate()->shouldBeCalled();
        $this->interact()->shouldReturn($proposal);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Text $text
     */
    function it_carries_out_the_text_submit_process($text)
    {
        $this->beConstructedWith($text);
        $text->validate()->shouldBeCalled();
        $this->interact()->shouldReturn($text);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Paste $paste
     */
    function it_carries_out_the_paste_submit_process($paste)
    {
        $this->beConstructedWith($paste);
        $paste->validate()->shouldBeCalled();
        $this->interact()->shouldReturn($paste);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Image $image
     */
    function it_carries_out_the_image_submit_process($image)
    {
        $this->beConstructedWith($image);
        $image->validate()->shouldBeCalled();
        $image->generate_thumbnail()->shouldBeCalled();
        $image->calculate_dimensions()->shouldBeCalled();
        $this->interact()->shouldReturn($image);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Sound $sound
     */
    function it_carries_out_the_sound_submit_process($sound)
    {
        $this->beConstructedWith($sound);
        $sound->validate()->shouldBeCalled();
        $sound->generate_thumbnail()->shouldBeCalled();
        $sound->calculate_length()->shouldBeCalled();
        $sound->calculate_filesize()->shouldBeCalled();
        $this->interact()->shouldReturn($sound);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Video $video
     */
    function it_carries_out_the_video_submit_process($video)
    {
        $this->beConstructedWith($video);
        $video->validate()->shouldBeCalled();
        $video->encode_to_webm()->shouldBeCalled();
        $video->generate_thumbnail()->shouldBeCalled();
        $video->calculate_length()->shouldBeCalled();
        $video->calculate_filesize()->shouldBeCalled();
        $video->calculate_dimensions()->shouldBeCalled();
        $this->interact()->shouldReturn($video);
    }

    /**
     * @param Eadrax\Core\Usecase\Update\Prepare\Website $website
     */
    function it_carries_out_the_website_submit_process($website)
    {
        $this->beConstructedWith($website);
        $website->validate()->shouldBeCalled();
        $website->generate_thumbnail()->shouldBeCalled();
        $this->interact()->shouldReturn($website);
    }
}
