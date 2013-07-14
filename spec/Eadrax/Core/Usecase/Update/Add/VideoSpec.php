<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class VideoSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Video $video
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($video, $project, $repository)
    {
        $project->id = 'project_id';
        $video->project = $project;
        $video->private = 'update_private';
        $video->file = 'tmp_name.webm';
        $video->thumbnail = 'tmp_name.webm.thumb.png';
        $video->length = 'length';
        $video->filesize = 'filesize';
        $video->width = 'width';
        $video->height = 'height';
        $this->beConstructedWith($repository);
        $this->load_prepared_proposal($video);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Video');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_should_be_a_video()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Video');
    }

    function it_submits_the_video($repository)
    {
        $repository->save_generated_file('tmp_name.webm')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.webm.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_video('project_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize', 'width', 'height')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
