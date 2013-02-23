<?php

namespace spec\Eadrax\Core\Usecase\Project\Prepare;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $data_project
     * @param Eadrax\Core\Tool\Validation $tool_validation
     */
    function let($data_project, $tool_validation)
    {
        $data_project->name = 'foo';
        $this->beConstructedWith($data_project, $tool_validation);
        $this->name->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_catches_invalid_proposal_information($tool_validation)
    {
        $tool_validation->setup(array(
            'name' => $this->name,
            'summary' => $this->summary,
            'website' => $this->website
        ))->shouldBeCalled();
        $tool_validation->rule('name', 'not_empty')->shouldBeCalled();
        $tool_validation->rule('summary', 'not_empty')->shouldBeCalled();
        $tool_validation->rule('website', 'url')->shouldBeCalled();
        $tool_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $tool_validation->errors()->shouldBeCalled()->willReturn(array('foo'));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_allows_valid_proposal_information($tool_validation)
    {
        $tool_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }
}
