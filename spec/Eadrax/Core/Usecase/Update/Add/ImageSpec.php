<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class ImageSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($image, $project, $file, $repository)
    {
        $project->id = 'project_id';
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $image->project = $project;
        $image->private = 'update_private';
        $image->file = $file;
        $image->thumbnail = 'tmp_name.thumb.png';
        $image->width = 'width';
        $image->height = 'height';

        $this->beConstructedWith($image, $repository);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Image');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_should_be_an_image()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Image');
    }

    function it_submits_the_image($repository)
    {
        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_image('project_id', 'update_private', 'file_path', 'thumbnail_path', 'width', 'height')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
