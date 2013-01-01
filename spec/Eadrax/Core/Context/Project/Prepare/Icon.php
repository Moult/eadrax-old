<?php

namespace spec\Eadrax\Core\Context\Project\Prepare;

use PHPSpec2\ObjectBehavior;

class Icon extends ObjectBehavior
{
    /**
     * @param \Eadrax\Core\Data\File                          $data_file
     * @param \Eadrax\Core\Context\Project\Prepare\Repository $repository
     * @param \Eadrax\Core\Entity\Image                       $entity_image
     * @param \Eadrax\Core\Entity\Validation                  $entity_validation
     */
    function let($data_file, $repository, $entity_image, $entity_validation)
    {
        $this->beConstructedWith($data_file, $repository, $entity_image, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Prepare\Icon');
    }

    function it_should_catch_invalid_icon_information($entity_validation)
    {
        $entity_validation->setup(array(
            'metadata' => array(
                'name' => $this->get_name(),
                'type' => $this->get_mimetype(),
                'tmp_name' => $this->get_tmp_name(),
                'error' => $this->get_error(),
                'size' => $this->get_filesize()
            )
        ))->shouldBeCalled();
        $entity_validation->rule('metadata', 'upload_valid')->shouldBeCalled();
        $entity_validation->rule('metadata', 'upload_type', array('jpg', 'png'))->shouldBeCalled();
        $entity_validation->rule('metadata', 'upload_size', '1M')->shouldBeCalled();
        $entity_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $entity_validation->errors()->shouldBeCalled()->willReturn(array('foo' => 'bar'));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_allow_valid_icon_information($entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_be_able_to_upload_the_icon($repository, $entity_image)
    {
        $repository->save_icon($this)->shouldBeCalled();
        $entity_image->resize(50, 50)->shouldBeCalled();
        $this->upload();
    }
}
