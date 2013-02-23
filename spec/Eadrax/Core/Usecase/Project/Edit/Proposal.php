<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $data_project
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $repository
     */
    function let($data_project, $repository)
    {
        $this->beConstructedWith($data_project, $repository);
    }
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Proposal');
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    function it_should_be_able_to_update_the_existing_project($repository)
    {
        $repository->update_project($this)->shouldBeCalled();
        $this->update();
    }
}
