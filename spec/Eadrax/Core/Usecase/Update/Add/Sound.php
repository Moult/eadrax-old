<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Sound extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Soundeditor $soundeditor
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($sound, $project, $file, $repository, $filemanager, $soundeditor, $validator)
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
        $this->beConstructedWith($sound, $repository, $filemanager, $soundeditor, $validator);
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

    function it_validates($validator)
    {
        $supported_filetypes = array('ogg', 'mp3', 'wav');

        $validator->setup(array(
            'file' => array(
                'name' => 'name',
                'tmp_name' => 'tmp_name',
                'type' => 'mimetype',
                'size' => 'filesize_in_bytes',
                'error' => 'error_code'
            )
        ))->shouldBeCalled();
        $validator->rule('file', 'not_empty')->shouldBeCalled();
        $validator->rule('file', 'upload_valid')->shouldBeCalled();
        $validator->rule('file', 'upload_type', $supported_filetypes)->shouldBeCalled();
        $validator->rule('file', 'upload_size', '100M')->shouldBeCalled();
        $validator->check()->shouldBeCalled();
        $validator->errors()->shouldBeCalled()->willReturn(array('file'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_generates_a_thumbnail($soundeditor)
    {
        $soundeditor->setup('tmp_name', 'tmp_name.thumb.png')->shouldBeCalled();
        $soundeditor->thumbnail(300, 100)->shouldBeCalled();
        $this->generate_thumbnail();
        $this->thumbnail->shouldBe('tmp_name.thumb.png');
    }

    function it_should_calculate_length($soundeditor)
    {
        $soundeditor->setup('tmp_name')->shouldBeCalled();
        $soundeditor->get_length()->shouldBeCalled()->willReturn('length');
        $this->calculate_length();
        $this->length->shouldBe('length');
    }

    function it_can_calculate_filesize($filemanager)
    {
        $filemanager->get_file_size('tmp_name')->shouldBeCalled()->willReturn('filesize');
        $this->calculate_filesize();
        $this->filesize->shouldBe('filesize');
    }

    function it_submits_the_sound($sound, $project, $file, $repository, $filemanager, $soundeditor, $validator)
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

        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_sound('project_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize')->shouldBeCalled()->willReturn('update_id');

        $soundeditor->get_length()->shouldBeCalled()->willReturn('length');
        $filemanager->get_file_size('tmp_name')->shouldBeCalled()->willReturn('filesize');

        $this->beConstructedWith($sound, $repository, $filemanager, $soundeditor, $validator);

        $this->generate_thumbnail();
        $this->calculate_length();
        $this->calculate_filesize();
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
