<?php

namespace spec\Eadrax\Core\Usecase\Hook\Delete;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Hook\Delete\Project $project
     * @param Eadrax\Core\Usecase\Hook\Delete\Service $service
     */
    function let($project, $service)
    {
        $this->beConstructedWith($project, $service);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Delete\Interactor');
    }

    function it_deletes_hooks($project, $service)
    {
        $project->authorise()->shouldBeCalled();
        $project->remove_service($service)->shouldBeCalled();
        $this->interact();
    }
}
