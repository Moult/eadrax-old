<?php

namespace spec\Eadrax\Core\Usecase\Update;

use PHPSpec2\ObjectBehavior;

class Delete extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\Update $update_details
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Delete\Repository $update_delete
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Filesystem $filesystem
     */
    function let($update, $update_details, $project, $update_delete, $auth, $filesystem)
    {
        $update->id = 'id';
        $update_delete->get_parent_project($update)->willReturn($project);
        $update_details->type = 'text';
        $update_details->content = 'foo';
        $update_delete->get_update_type_and_content('id')->willReturn($update_details);

        $data = array(
            'update' => $update
        );

        $repositories = array(
            'update_delete' => $update_delete
        );

        $tools = array(
            'auth' => $auth,
            'filesystem' => $filesystem
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Interactor');
    }
}
