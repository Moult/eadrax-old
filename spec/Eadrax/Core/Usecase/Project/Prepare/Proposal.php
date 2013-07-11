<?php

namespace spec\Eadrax\Core\Usecase\Project\Prepare;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $validator)
    {
        $project->name = 'Project name';
        $project->summary = 'Project summary';
        $project->website = 'Project website';

        $this->beConstructedWith($project, $validator);

        $this->name->shouldBe('Project name');
        $this->summary->shouldBe('Project summary');
        $this->website->shouldBe('Project website');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_catches_invalid_proposal_information($validator)
    {
        $validator->setup(array(
            'name' => $this->name,
            'summary' => $this->summary,
            'website' => $this->website
        ))->shouldBeCalled();
        $validator->rule('name', 'not_empty')->shouldBeCalled();
        $validator->rule('summary', 'not_empty')->shouldBeCalled();
        $validator->rule('website', 'url')->shouldBeCalled();
        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array('foo'));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_allows_valid_proposal_information($validator)
    {
        $validator->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }
}
