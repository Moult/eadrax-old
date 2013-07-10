<?php

namespace spec\Eadrax\Core\Usecase\Hook;

use PHPSpec2\ObjectBehavior;

class Delete extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\Hook $hook
     * @param Eadrax\Core\Usecase\Hook\Delete\Repository $hook_delete
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($project, $hook, $hook_delete, $authenticator)
    {
        $data = array(
            'project' => $project,
            'hook' => $hook
        );

        $repositories = array(
            'hook_delete' => $hook_delete
        );

        $tools = array(
            'authenticator' => $authenticator
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Interactor');
    }
}
