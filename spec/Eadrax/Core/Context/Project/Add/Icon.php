<?php

namespace spec\Eadrax\Core\Context\Project\Add;

use PHPSpec2\ObjectBehavior;

class Icon extends ObjectBehavior
{
    /**
     * @param \Eadrax\Core\Data\File                    $data_file
     * @param \Eadrax\Core\Context\Project\Add\Proposal $proposal
     * @param \Eadrax\Core\Entity\Validation            $entity_validation
     */
    function let($data_file, $proposal, $entity_validation)
    {
        $this->beConstructedWith($data_file);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Add\Icon');
    }

    function it_should_check_whether_or_not_it_exists($data_file, $proposal)
    {
        $proposal->submit()->shouldBeCalled();
        $data_file->name = '';
        $this->beConstructedWith($data_file);
        $this->link(array('proposal' => $proposal));
        $this->exists();
    }

    function it_should_validate_icon_information($data_file, $entity_validation)
    {
        $data_file->name = 'foo';
        $this->beConstructedWith($data_file);
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
        $this->shouldThrow('\Eadrax\Core\Exception\Validation', 'Multiple exceptions thrown: foo')->duringExists();
    }

    function it_should_save_the_icon_if_valid($proposal, $repository, $entity_validation, $entity_image)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $repository->save_icon($this)->shouldBeCalled();
        $entity_image->resize(50, 50)->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $this->link(array('proposal' => $proposal, 'repository' => $repository, 'entity_validation' => $entity_validation, 'entity_image' => $entity_image));
        $this->validate_information();
    }
}
