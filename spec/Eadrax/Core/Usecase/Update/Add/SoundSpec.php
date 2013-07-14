<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class SoundSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     */
    function let($sound, $project, $file, $repository)
    {
        $project->id = 'project_id';
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $sound->project = $project;
        $sound->private = 'update_private';
        $sound->file = $file;
        $sound->thumbnail = 'tmp_name.thumb.png';
        $sound->length = 'length';
        $sound->filesize = 'filesize';
        $this->beConstructedWith($sound, $repository);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Sound');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Proposal');
    }

    function it_should_be_a_sound()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Sound');
    }

    function it_submits_the_sound($repository)
    {
        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_sound('project_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize')->shouldBeCalled()->willReturn('update_id');

        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
