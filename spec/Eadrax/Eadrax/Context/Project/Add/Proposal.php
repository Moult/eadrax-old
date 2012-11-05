<?php

namespace spec\Eadrax\Eadrax\Context\Project\Add;

require_once 'spec/Eadrax/Eadrax/Context/Project/Add/Proposal/Interaction.php';
require_once 'spec/Eadrax/Eadrax/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context;

class Proposal extends ObjectBehavior
{
    use Context\Interaction, Proposal\Interaction;

    /**
     * @param Eadrax\Eadrax\Context\Project\Add\Repository $repository
     * @param Eadrax\Eadrax\Entity\Validation              $entity_validation
     */
    function let($repository, $entity_validation)
    {
        $model_project = new \Eadrax\Eadrax\Model\Project;
        $model_project->name = 'foo';
        $this->beConstructedWith($model_project, $repository, $entity_validation);
        $this->name->shouldBe('foo');
    }

    function it_should_be_initializable($repository, $entity_validation)
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\Proposal');
    }

    function it_should_be_a_proposal_role()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Model\Project');
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\Proposal\Requirement');
    }

    function it_should_be_able_to_load_data()
    {
        $model_project = new \Eadrax\Eadrax\Model\Project;
        $model_project->name = 'bar';
        $this->assign_data($model_project);
        $this->name->shouldBe('bar');
    }

    function it_should_construct_links()
    {
        $this->repository->shouldHaveType('Eadrax\Eadrax\Context\Project\Add\Repository');
        $this->entity_validation->shouldHaveType('Eadrax\Eadrax\Entity\Validation');
    }
}
