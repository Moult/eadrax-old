<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Edit extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $project_edit
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($user, $project_edit, $auth, $validation)
    {
        $data = array(
            'id' => 'Project id',
            'name' => 'Project name',
            'summary' => 'Project summary',
            'description' => 'Project description',
            'website' => 'http://projectwebsite.com/'
        );
        $repositories = array(
            'project_edit' => $project_edit
        );
        $auth->get_user()->willReturn($user);
        $tools = array(
            'auth' => $auth,
            'validation' => $validation
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
