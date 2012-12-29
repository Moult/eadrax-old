<?php

namespace spec\Eadrax\Core\Context\Project;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Prepare extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Core\Data\User                          $data_user
     * @param \Eadrax\Core\Data\Project                       $data_project
     * @param \Eadrax\Core\Data\File                          $data_file
     * @param \Eadrax\Core\Context\Project\Prepare\Repository $repository
     * @param \Eadrax\Core\Entity\Validation                  $entity_validation
     * @param \Eadrax\Core\Entity\Image                       $entity_image
     */
    function let($data_user, $data_project, $data_file, $repository, $entity_validation, $entity_image)
    {
        $data_project->get_author()->willReturn($data_user);
        $data_project->get_icon()->willReturn($data_file);
        $this->beConstructedWith($data_project, $repository, $entity_validation, $entity_image);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Prepare');
    }

    function it_assigns_data_to_roles()
    {
        $this->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Prepare\Proposal');
        $this->proposal->repository->shouldHaveType('\Eadrax\Core\Context\Project\Prepare\Repository');
        $this->proposal->entity_validation->shouldHaveType('\Eadrax\Core\Entity\Validation');
        $this->icon->shouldHaveType('\Eadrax\Core\Context\Project\Prepare\Icon');
        $this->icon->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Prepare\Proposal');
        $this->icon->entity_validation->shouldHaveType('\Eadrax\Core\Entity\Validation');
        $this->icon->entity_image->shouldHaveType('\Eadrax\Core\Entity\Image');
        $this->icon->repository->shouldHaveType('\Eadrax\Core\Context\Project\Prepare\Repository');
    }

    function it_catches_validation_exceptions_during_usecase_execution($entity_validation)
    {
        $entity_validation->errors()->willReturn(array('foo'));
        $entity_validation->check()->willReturn(FALSE);

        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_succesfully($entity_validation)
    {
        $entity_validation->check()->willReturn(TRUE);
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
