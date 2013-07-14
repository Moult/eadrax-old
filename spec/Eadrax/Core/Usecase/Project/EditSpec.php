<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PhpSpec\ObjectBehavior;

class EditSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $project_edit
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($project, $project_edit, $authenticator, $validator)
    {
        $data = array(
            'project' => $project
        );
        $repositories = array(
            'project_edit' => $project_edit
        );
        $tools = array(
            'authenticator' => $authenticator,
            'validator' => $validator
        );
        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Edit');
    }

    function it_loads_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Edit\Interactor');
    }
}
