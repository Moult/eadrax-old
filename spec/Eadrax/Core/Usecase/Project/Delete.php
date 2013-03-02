<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Delete extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Project\Delete\Repository $project_delete
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Data\User $user
     */
    function let($project, $project_delete, $auth, $user)
    {
        $auth->get_user()->willReturn($user);
        $data = array(
            'project' => $project
        );
        $repositories = array(
            'project_delete' => $project_delete
        );
        $tools = array(
            'auth' => $auth
        );
        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Delete\Interactor');
    }
}
