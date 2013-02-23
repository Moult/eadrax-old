<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Prepare extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Data\Project $data_project
     * @param Eadrax\Core\Data\File $data_file
     * @param Eadrax\Core\Usecase\Project\Prepare\Repository $repository
     * @param Eadrax\Core\Tool\Validation $tool_validation
     * @param Eadrax\Core\Tool\Image $tool_image
     */
    function let($data_user, $data_project, $data_file, $repository, $tool_validation, $tool_image)
    {
        $data_project->get_author()->willReturn($data_user);
        $data_project->get_icon()->willReturn($data_file);
        $this->beConstructedWith($data_project, $repository, $tool_validation, $tool_image);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\Project\Prepare\Interactor');
    }
}
