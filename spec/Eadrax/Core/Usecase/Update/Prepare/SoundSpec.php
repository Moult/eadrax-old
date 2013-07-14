<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;

class SoundSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Sound $sound
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Tool\Filemanager $filemanager
     * @param Eadrax\Core\Tool\Soundeditor $soundeditor
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($sound, $project, $file, $filemanager, $soundeditor, $validator)
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
        $this->beConstructedWith($sound, $filemanager, $soundeditor, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Sound');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Proposal');
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
}
