<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class Proposal extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $repository
     */
    function let($project, $repository)
    {
        $project->id = 'Project id';
        $project->name = 'Project name';
        $project->summary = 'Project summary';
        $project->description = 'Project description';
        $project->website = 'Project website';

        $this->beConstructedWith($project, $repository);

        $this->id->shouldBe('Project id');
        $this->name->shouldBe('Project name');
        $this->summary->shouldBe('Project summary');
        $this->description->shouldBe('Project description');
        $this->website->shouldBe('Project website');
        $this->last_updated->shouldBe(time());
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Proposal');
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Edit\User $impostor
     * @param Eadrax\Core\Usecase\Project\Edit\User $owner
     */
    function it_does_not_authorise_users_who_do_not_own_the_project($impostor, $owner, $repository)
    {
        $impostor->id = '42';
        $owner->id = '24';
        $repository->get_owner($this)->shouldBeCalled()->willReturn($owner);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')
            ->duringVerify_ownership($impostor);
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Edit\User $owner
     */
    function it_authorises_users_who_own_the_project($owner, $repository)
    {
        $owner->id = '24';
        $repository->get_owner($this)->shouldBeCalled()->willReturn($owner);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')
            ->duringVerify_ownership($owner);
    }

    function it_should_be_able_to_update_the_existing_project($repository)
    {
        $repository->update($this)->shouldBeCalled();
        $this->update();
    }
}
