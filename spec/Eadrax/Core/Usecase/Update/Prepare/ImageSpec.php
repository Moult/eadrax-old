<?php

namespace spec\Eadrax\Core\Usecase\Update\Prepare;

use PhpSpec\ObjectBehavior;

class ImageSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Image $image
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\File $file
     * @param Eadrax\Core\Tool\Photoshopper $photoshopper
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($image, $project, $file, $photoshopper, $validator)
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
        $this->beConstructedWith($image, $photoshopper, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Image');
    }

    function it_should_be_a_proposal()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Prepare\Proposal');
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
        $validator->rule('image', 'not_empty')->shouldBeCalled();
        $validator->rule('image', 'upload_valid')->shouldBeCalled();
        $validator->rule('image', 'upload_type', $supported_filetypes)->shouldBeCalled();
        $validator->rule('image', 'upload_size', '100M')->shouldBeCalled();
        $validator->check()->shouldBeCalled();
        $validator->errors()->shouldBeCalled()->willReturn(array('image'));
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
}
