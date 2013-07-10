<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Add extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Add\Repository $project_add
     * @param Eadrax\Core\Usecase\Project\Prepare\Repository $project_prepare
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $project_add, $project_prepare, $authenticator, $validator)
    {
        $data = array(
            'project' => $project
        );
        $repositories = array(
            'project_add' => $project_add,
            'project_prepare' => $project_prepare
        );
        $tools = array(
            'authenticator' => $authenticator,
            'validator' => $validator
        );
        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Add\Interactor');
    }
}
