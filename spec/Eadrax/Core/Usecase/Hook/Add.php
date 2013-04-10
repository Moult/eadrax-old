<?php

namespace spec\Eadrax\Core\Usecase\Hook;

use PHPSpec2\ObjectBehavior;

class Add extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\Hook $hook
     * @param Eadrax\Core\Usecase\Hook\Add\Repository $hook_add
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($project, $hook, $hook_add, $validation)
    {
        $data = array(
            'project' => $project,
            'hook' => $hook
        );

        $repositories = array(
            'hook_add' => $hook_add
        );

        $tools = array(
            'validation' => $validation
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Hook\Add\Interactor');
    }
}
