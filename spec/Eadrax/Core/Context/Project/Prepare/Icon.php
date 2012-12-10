<?php

namespace spec\Eadrax\Core\Context\Project\Prepare;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Interaction;

class Icon extends ObjectBehavior
{
    use Interaction;

    /**
     * @param \Eadrax\Core\Data\File                    $data_file
     * @param \Eadrax\Core\Context\Project\Prepare\Proposal $proposal
     * @param \Eadrax\Core\Entity\Validation            $entity_validation
     */
    function let($data_file)
    {
        $this->beConstructedWith($data_file);
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
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_allow_valid_icon_information($entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_should_be_able_to_upload_the_icon($repository, $entity_image)
    {
        $repository->save_icon($this)->shouldBeCalled();
        $entity_image->resize(50, 50)->shouldBeCalled();
        $this->link(array('repository' => $repository, 'entity_image' => $entity_image));
        $this->upload();
    }
}
