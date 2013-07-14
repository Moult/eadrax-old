<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PhpSpec\ObjectBehavior;

class VideoSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Video $video
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Videoeditor $videoeditor
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($video, $project, $file, $repository, $filemanager, $videoeditor, $validator)
    {
        $project->id = 'project_id';
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $video->project = $project;
        $video->private = 'update_private';
        $video->file = $file;
        $this->beConstructedWith($video, $repository, $filemanager, $videoeditor, $validator);
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

    function it_validates($validator)
    {
        $supported_filetypes = array('ogg', 'ogv', 'wmv', 'avi', 'mpg', 'mpeg', 'mov');

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

    function it_encodes_to_webm($videoeditor)
    {
        $videoeditor->setup('tmp_name', 'tmp_name.webm')->shouldBeCalled();
        $videoeditor->encode_to_webm()->shouldBeCalled();
        $this->encode_to_webm();
        $this->file->shouldBe('tmp_name.webm');
    }

    function it_generates_a_thumbnail($videoeditor)
    {
        $videoeditor->setup('tmp_name', 'tmp_name.webm')->shouldBeCalled();
        $videoeditor->encode_to_webm()->shouldBeCalled();
        $this->encode_to_webm();

        $videoeditor->setup('tmp_name.webm', 'tmp_name.webm.thumb.png')->shouldBeCalled();
        $videoeditor->thumbnail(300, 100)->shouldBeCalled();
        $this->generate_thumbnail();
        $this->thumbnail->shouldBe('tmp_name.webm.thumb.png');
    }

    function it_should_calculate_length($videoeditor)
    {
        $videoeditor->setup('tmp_name', 'tmp_name.webm')->shouldBeCalled();
        $videoeditor->encode_to_webm()->shouldBeCalled();
        $this->encode_to_webm();

        $videoeditor->setup('tmp_name.webm')->shouldBeCalled();
        $videoeditor->get_length()->shouldBeCalled()->willReturn('length');
        $this->calculate_length();
        $this->length->shouldBe('length');
    }

    function it_can_calculate_filesize($filemanager)
    {
        $filemanager->get_file_size('tmp_name.webm')->shouldBeCalled()->willReturn('filesize');
        $this->encode_to_webm();
        $this->calculate_filesize();
        $this->filesize->shouldBe('filesize');
    }

    function it_calculates_dimensions($videoeditor)
    {
        $videoeditor->setup('tmp_name', 'tmp_name.webm')->shouldBeCalled();
        $videoeditor->encode_to_webm()->shouldBeCalled();
        $this->encode_to_webm();

        $videoeditor->setup('tmp_name.webm')->shouldBeCalled();
        $videoeditor->get_dimensions()->shouldBeCalled()->willReturn(array('width', 'height'));
        $this->calculate_dimensions();
        $this->width->shouldBe('width');
        $this->height->shouldBe('height');
    }

    function it_submits_the_video($video, $project, $file, $repository, $filemanager, $videoeditor, $validator)
    {
        $project->id = 'project_id';
        $file->name = 'name';
        $file->tmp_name = 'tmp_name';
        $file->mimetype = 'mimetype';
        $file->filesize_in_bytes = 'filesize_in_bytes';
        $file->error_code = 'error_code';
        $video->project = $project;
        $video->private = 'update_private';
        $video->file = $file;

        $repository->save_generated_file('tmp_name.webm')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.webm.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_video('project_id', 'update_private', 'file_path', 'thumbnail_path', 'length', 'filesize', 'width', 'height')->shouldBeCalled()->willReturn('update_id');

        $videoeditor->setup('tmp_name', 'tmp_name.webm')->shouldBeCalled();
        $videoeditor->encode_to_webm()->shouldBeCalled();

        $videoeditor->setup('tmp_name.webm', 'tmp_name.webm.thumb.png')->shouldBeCalled();
        $videoeditor->thumbnail(300, 100)->shouldBeCalled();

        $videoeditor->setup('tmp_name.webm')->shouldBeCalled();
        $videoeditor->get_dimensions()->shouldBeCalled()->willReturn(array('width', 'height'));

        $videoeditor->get_length()->shouldBeCalled()->willReturn('length');
        $filemanager->get_file_size('tmp_name.webm')->shouldBeCalled()->willReturn('filesize');

        $this->beConstructedWith($video, $repository, $filemanager, $videoeditor, $validator);

        $this->encode_to_webm();
        $this->generate_thumbnail();
        $this->calculate_length();
        $this->calculate_filesize();
        $this->calculate_dimensions();
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
