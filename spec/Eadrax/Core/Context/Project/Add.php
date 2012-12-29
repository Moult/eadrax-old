<?php

namespace spec\Eadrax\Core\Context\Project;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Add extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Core\Data\User                          $data_user
     * @param \Eadrax\Core\Data\Project                       $data_project
     * @param \Eadrax\Core\Data\File                          $data_file
     * @param \Eadrax\Core\Context\Project\Add\Repository     $repository
     * @param \Eadrax\Core\Context\Project\Prepare\Repository $repository_project_prepare
     * @param \Eadrax\Core\Entity\Auth                        $entity_auth
     * @param \Eadrax\Core\Entity\Validation                  $entity_validation
     * @param \Eadrax\Core\Entity\Image                       $entity_image
     */
    function let($data_user, $data_project, $data_file, $repository, $repository_project_prepare, $entity_auth, $entity_validation, $entity_image)
    {
        $data_project->get_author()->willReturn($data_user);
        $data_project->get_icon()->willReturn($data_file);
        $this->beConstructedWith($data_project, $repository, $repository_project_prepare, $entity_auth, $entity_validation, $entity_image);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Add');
    }

    function it_assigns_data_to_roles()
    {
        $this->user->shouldHaveType('\Eadrax\Core\Context\Project\Add\User');
        $this->user->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Add\Proposal');
        $this->user->entity_auth->shouldHaveType('\Eadrax\Core\Entity\Auth');
        $this->proposal->shouldHaveType('\Eadrax\Core\Context\Project\Add\Proposal');
        $this->proposal->repository->shouldHaveType('\Eadrax\Core\Context\Project\Add\Repository');
    }

    function it_catches_authorisation_exceptions_during_usecase_execution($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);

        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('Please login before you can add a new project.')
            )
        ));
    }

    function it_catches_validation_exceptions_during_usecase_execution($data_user, $entity_auth, $entity_validation)
    {
        $entity_auth->get_user()->willReturn($data_user);
        $entity_auth->logged_in()->willReturn(TRUE);
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

    function it_executes_the_usecase_succesfully($data_user, $entity_auth, $entity_validation)
    {
        $entity_auth->get_user()->willReturn($data_user);
        $entity_auth->logged_in()->willReturn(TRUE);
        $entity_validation->check()->willReturn(TRUE);

        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
