<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Image extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($image, $project, $file, $repository, $photoshopper, $validator)
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
        $this->beConstructedWith($image, $repository, $photoshopper, $validator);
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

    function it_validates($validator)
    {
        $supported_filetypes = array('gif', 'jpg', 'jpeg', 'png');

        $validator->setup(array(
            'file' => array(
                'name' => 'name',
                'tmp_name' => 'tmp_name',
                'type' => 'mimetype',
                'size' => 'filesize_in_bytes',
                'error' => 'error_code'
            )
        ))->shouldBeCalled();
        $validator->rule('content', 'not_empty')->shouldBeCalled();
        $validator->rule('content', 'upload_valid')->shouldBeCalled();
        $validator->rule('content', 'upload_type', $supported_filetypes)->shouldBeCalled();
        $validator->rule('content', 'upload_size', '100M')->shouldBeCalled();
        $validator->check()->shouldBeCalled();
        $validator->errors()->shouldBeCalled()->willReturn(array('content'));
        $this->shouldThrow('Eadrax\Core\Exception\Validation')
            ->duringValidate();
    }

    function it_generates_a_thumbnail($photoshopper)
    {
        $photoshopper->setup('tmp_name', 'tmp_name.thumb.png')->shouldBeCalled();
        $photoshopper->resize_to_height(100)->shouldBeCalled();
        $this->generate_thumbnail();
        $this->thumbnail->shouldBe('tmp_name.thumb.png');
    }

    function it_should_calculate_dimensions($photoshopper)
    {
        $photoshopper->setup('tmp_name')->shouldBeCalled();
        $photoshopper->get_dimensions()->shouldBeCalled()->willReturn(array('width', 'height'));
        $this->calculate_dimensions();
        $this->width->shouldBe('width');
        $this->height->shouldBe('height');
    }

    function it_submits_the_image($image, $project, $file, $repository, $photoshopper, $validator)
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

        $repository->save_file('name', 'tmp_name', 'mimetype', 'filesize_in_bytes', 'error_code')->shouldBeCalled()->willReturn('file_path');
        $repository->save_generated_file('tmp_name.thumb.png')->shouldBeCalled()->willReturn('thumbnail_path');
        $repository->save_image('project_id', 'update_private', 'file_path', 'thumbnail_path', 'width', 'height')->shouldBeCalled()->willReturn('update_id');

        $photoshopper->get_dimensions()->shouldBeCalled()->willReturn(array('width', 'height'));

        $this->beConstructedWith($image, $repository, $photoshopper, $validator);

        $this->generate_thumbnail();
        $this->calculate_dimensions();
        $this->submit();
        $this->id->shouldBe('update_id');
        $this->get_id()->shouldReturn('update_id');
    }
}
