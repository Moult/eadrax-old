<?php

namespace spec\Eadrax\Core\Context\Project\Add;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Interaction;

class Proposal extends ObjectBehavior
{
    use Interaction;

    /**
     * @param Eadrax\Core\Data\Project                   $data_project
     * @param Eadrax\Core\Context\Project\Add\Icon       $icon
     * @param Eadrax\Core\Context\Project\Add\Repository $repository
     * @param Eadrax\Core\Entity\Validation              $entity_validation
     */
    function let($data_project)
    {
        $data_project->name = 'foo';
        $this->beConstructedWith($data_project);
        $this->name->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Add\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_catches_invalid_proposal_information($entity_validation)
    {
        $entity_validation->setup(array(
            'name' => $this->get_name(),
            'summary' => $this->get_summary(),
            'website' => $this->get_website()
        ))->shouldBeCalled();
        $entity_validation->rule('name', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('summary', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('website', 'url')->shouldBeCalled();
        $entity_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $entity_validation->errors()->shouldBeCalled()->willReturn(array('foo'));
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_allows_valid_proposal_information($entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_submits_the_proposal_to_the_repository($repository)
    {
        $repository->add_project($this)->shouldBeCalled();
        $this->link(array('repository' => $repository));
        $this->submit();
    }
}
