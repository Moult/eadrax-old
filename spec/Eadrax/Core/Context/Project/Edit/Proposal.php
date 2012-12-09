<?php

namespace spec\Eadrax\Core\Context\Project\Edit;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Interaction;

class Proposal extends ObjectBehavior
{
    use Interaction;

    /**
     * @param \Eadrax\Core\Data\Project $data_project
     */
    function let($data_project)
    {
        $data_project->name = 'foo';
        $this->beConstructedWith($data_project);
        $this->get_name()->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Edit\Proposal');
    }

    function it_is_a_role()
    {
        $this->shouldHaveType('\Eadrax\Core\Data\Project');
    }
}
