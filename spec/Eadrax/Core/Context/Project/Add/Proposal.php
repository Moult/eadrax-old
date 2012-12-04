<?php

namespace spec\Eadrax\Core\Context\Project\Add;

require_once 'spec/Eadrax/Core/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context;

class Proposal extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Core\Data\Project                   $data_project
     * @param Eadrax\Core\Context\Project\Add\Icon       $icon
     * @param Eadrax\Core\Context\Project\Add\Repository $repository
     * @param Eadrax\Core\Entity\Validation              $entity_validation
     */
    function let($data_project, $icon, $repository, $entity_validation)
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

    function it_sets_author_from_the_passed_data_user_then_validates($entity_validation)
    {
        $data_user = new \Eadrax\Core\Data\User();

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

        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringAssign_author($data_user);
        $this->get_author()->shouldBe($data_user);
    }

    function it_validates_the_icon_if_project_has_valid_information($icon, $entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $icon->exists()->shouldBeCalled()->willReturn('foo');
        $this->link(array('icon' => $icon, 'entity_validation' => $entity_validation));
        $this->validate_information()->shouldReturn('foo');
    }

    function it_submits_the_proposal_to_the_repository($icon, $repository, $entity_validation)
    {
        $repository->add_project($this)->shouldBeCalled();
        $this->link(array('repository' => $repository));
        $this->submit();
    }
}
