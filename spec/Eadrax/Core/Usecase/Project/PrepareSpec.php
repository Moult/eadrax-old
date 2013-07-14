<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PhpSpec\ObjectBehavior;

class PrepareSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $validator)
    {
        $data = array(
            'project' => $project
        );
        $tools = array(
            'validator' => $validator
        );
        $this->beConstructedWith($data, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Interactor');
    }
}
