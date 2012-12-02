<?php

namespace spec\Eadrax\Eadrax\Context\Project\Add;

require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Proposal extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Eadrax\Data\Project                   $data_project
     * @param Eadrax\Eadrax\Context\Project\Add\Repository $repository
     * @param Eadrax\Eadrax\Entity\Validation              $entity_validation
     */
    function let($data_project, $repository, $entity_validation)
    {
        $data_project->name = 'foo';
        $this->beConstructedWith($data_project);
        $this->name->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Data\Project');
    }

    function it_sets_author_from_the_passed_data_user_then_validates($entity_validation)
    {
        $data_user = new \Eadrax\Eadrax\Data\User();

        $entity_validation->setup(array(
            'name' => $this->get_name(),
            'summary' => $this->get_summary()
        ))->shouldBeCalled();
        $entity_validation->rule('name', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('summary', 'not_empty')->shouldBeCalled();
        $entity_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $entity_validation->errors()->shouldBeCalled()->willReturn(array('foo'));
        $this->link(array('entity_validation' => $entity_validation));

        $this->shouldThrow('\Eadrax\Eadrax\Exception\Validation')->duringAssign_author($data_user);
        $this->get_author()->shouldBe($data_user);
    }

    function it_submits_to_the_repository_if_project_has_valid_information($repository, $entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $repository->add_project($this)->shouldBeCalled()->willReturn('foo');
        $this->link(array('repository' => $repository, 'entity_validation' => $entity_validation));
        $this->validate_information()->shouldReturn('foo');
    }
}
