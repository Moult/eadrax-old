<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Update\Delete\Project $project
     * @param Eadrax\Core\Usecase\Update\Delete\Proposal $proposal
     */
    function let($project, $proposal)
    {
        $this->beConstructedWith($project, $proposal);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Interactor');
    }

    function it_should_run_the_usecase($project, $proposal)
    {
        $project->authorise()->shouldBeCalled();
        $proposal->delete_thumbnail()->shouldBeCalled();
        $proposal->delete_upload()->shouldBeCalled();
        $proposal->delete()->shouldBeCalled();
        $this->interact();
    }
}
