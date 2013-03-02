<?php

namespace spec\Eadrax\Core\Usecase\Project\Delete;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Delete\Repository $repository
     */
    function let($project, $repository)
    {
        $project->id = 42;
        $this->beConstructedWith($project, $repository);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\Proposal');
    }

    function it_is_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
        $this->id->shouldBe(42);
    }

    /**
     * @param Eadrax\Core\Data\User $owner
     */
    function it_verifies_project_ownership_with_logged_in_user($owner, $repository)
    {
        $owner->id = 42;
        $repository->get_owner($this)->shouldBeCalled()->willReturn($owner);
        $this->shouldNotThrow('Eadrax\Core\Exception\Authorisation')
            ->duringVerify_ownership($owner);
    }

    /**
     * @param Eadrax\Core\Data\User $owner
     * @param Eadrax\Core\Data\User $impostor
     */
    function it_does_not_verify_ownership_with_other_users($owner, $impostor, $repository)
    {
        $impostor->id = 24;
        $owner->id = 42;
        $repository->get_owner($this)->shouldBeCalled()->willReturn($owner);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringVerify_ownership($impostor);
    }

    function it_deletes_the_project($repository)
    {
        $repository->delete($this)->shouldBeCalled();
        $this->delete();
    }
}
