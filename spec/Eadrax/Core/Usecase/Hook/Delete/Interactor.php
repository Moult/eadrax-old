<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Hook $hook
     * @param Eadrax\Core\Usecase\Hook\Delete\Project $project
     */
    function let($hook, $project)
    {
        $hook->id = 'hook_id';
        $this->beConstructedWith($hook, $project);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Interactor');
    }

    function it_deletes_hooks($hook, $project)
    {
        $project->authorise()->shouldBeCalled();
        $project->remove_service('hook_id')->shouldBeCalled();
        $this->interact();
    }
}
