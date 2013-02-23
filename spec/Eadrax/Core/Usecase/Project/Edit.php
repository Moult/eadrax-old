<?php

namespace spec\Eadrax\Core\Usecase\Project;

use PHPSpec2\ObjectBehavior;

class Edit extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Data\Project $data_project
     * @param Eadrax\Core\Data\File $data_file
     * @param Eadrax\Core\Usecase\Project\Edit\Repository $repository
     * @param Eadrax\Core\Usecase\Project\Prepare\Repository $repository_project_prepare
     * @param Eadrax\Core\Tool\Auth $entity_auth
     * @param Eadrax\Core\Tool\Image $entity_image
     * @param Eadrax\Core\Tool\Validation $entity_validation
     */
    function let($data_user, $data_project, $data_file, $repository, $repository_project_prepare, $entity_auth, $entity_image, $entity_validation)
    {
        $data_project->get_author()->willReturn($data_user);
        $data_project->get_icon()->willReturn($data_file);
        $this->beConstructedWith($data_project, $repository, $repository_project_prepare, $entity_auth, $entity_image, $entity_validation);
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
