<?php

namespace spec\Eadrax\Core\Usecase\Project\Prepare;

use PHPSpec2\ObjectBehavior;

class Icon extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\File $data_file
     * @param Eadrax\Core\Usecase\Project\Prepare\Repository $repository
     * @param Eadrax\Core\Tool\Image $tool_image
     * @param Eadrax\Core\Tool\Validation $tool_validation
     */
    function let($data_file, $repository, $tool_image, $tool_validation)
    {
        $data_file->name = 'File name';
        $data_file->mimetype = 'File mimetype';
        $data_file->tmp_name = 'File tmp name';
        $data_file->error_code = 'File error code';
        $data_file->filesize_in_bytes = 'File size in bytes';
        $this->beConstructedWith($data_file, $repository, $tool_image, $tool_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Icon');
    }

    function it_should_catch_invalid_icon_information($tool_validation)
    {
        $tool_validation->setup(array(
            'metadata' => array(
                'name' => 'File name',
                'type' => 'File mimetype',
                'tmp_name' => 'File tmp name',
                'error' => 'File error code',
                'size' => 'File size in bytes'
            )
        ))->shouldBeCalled();
        $tool_validation->rule('metadata', 'upload_valid')->shouldBeCalled();
        $tool_validation->rule('metadata', 'upload_type', array('jpg', 'png'))->shouldBeCalled();
        $tool_validation->rule('metadata', 'upload_size', '1M')->shouldBeCalled();
        $tool_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $tool_validation->errors()->shouldBeCalled()->willReturn(array('foo' => 'bar'));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_allow_valid_icon_information($tool_validation)
    {
        $tool_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_be_able_to_upload_the_icon($repository, $tool_image)
    {
        $repository->save_icon($this)->shouldBeCalled();
        $tool_image->resize(50, 50)->shouldBeCalled();
        $this->upload();
    }
}
