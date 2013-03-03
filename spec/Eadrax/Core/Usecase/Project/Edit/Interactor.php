<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Edit\Author $author
     * @param Eadrax\Core\Usecase\Project\Prepare\Interactor $project_prepare
     * @param Eadrax\Core\Usecase\Project\Edit\Proposal $proposal
     */
    function let($author, $proposal, $project_prepare)
    {
        $this->beConstructedWith($author, $proposal, $project_prepare);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Interactor');
    }

    function it_runs_the_interaction_chain($author, $proposal, $project_prepare)
    {
        $author->authorise()->shouldBeCalled();
        $proposal->verify_ownership($author)->shouldBeCalled();
        $project_prepare->interact()->shouldBeCalled();
        $proposal->update()->shouldBeCalled();
        $this->interact();
    }
}
