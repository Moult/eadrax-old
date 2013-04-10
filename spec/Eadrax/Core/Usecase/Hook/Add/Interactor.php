<?php

namespace spec\Eadrax\Core\Usecase\Hook\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Hook\Add\Project $project
     * @param Eadrax\Core\Usecase\Hook\Add\Service $service
     */
    function let($project, $service)
    {
        $this->beConstructedWith($project, $service);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Hook\Add\Interactor');
    }

    function it_executes_the_interaction_chain($project, $service)
    {
        $project->has_service($service)->shouldBeCalled()->willReturn(FALSE);
        $service->is_valid()->shouldBeCalled();
        $project->add_service($service)->shouldBeCalled();
        $this->interact();
    }

    function it_doesnt_do_anything_if_the_hook_already_exists($project, $service)
    {
        $project->has_service($service)->shouldBeCalled()->willReturn(TRUE);
        $service->is_valid()->shouldNotBeCalled();
        $project->add_service($service)->shouldNotBeCalled();
        $this->interact();
    }
}
