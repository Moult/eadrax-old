<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PhpSpec\ObjectBehavior;

class AddSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Add\Repository $project_add
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $project_add, $authenticator, $validator)
    {
        $data = array(
            'project' => $project
        );
        $repositories = array(
            'project_add' => $project_add
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
