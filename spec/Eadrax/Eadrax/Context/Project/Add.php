<?php

namespace spec\Eadrax\Eadrax\Context\Project;

require_once 'spec/Eadrax/Eadrax/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context\Core;

class Add extends ObjectBehavior
{
    use Core;

    /**
     * @param \Eadrax\Eadrax\Data\User                      $data_user
     * @param \Eadrax\Eadrax\Data\Project                   $data_project
     * @param \Eadrax\Eadrax\Context\Project\Add\Repository $repository
     * @param \Eadrax\Eadrax\Entity\Auth                    $entity_auth
     * @param \Eadrax\Eadrax\Entity\Validation              $entity_validation
     */
    function let($data_user, $data_project, $repository, $entity_auth, $entity_validation)
    {
        $this->beConstructedWith($data_user, $data_project, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add');
    }

    function it_assigns_data_to_roles()
    {
        $this->user->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\User');
        $this->user->proposal->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\Proposal');
        $this->user->entity_auth->shouldHaveType('\Eadrax\Eadrax\Entity\Auth');
        $this->proposal->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\Proposal');
        $this->proposal->repository->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\Repository');
        $this->proposal->entity_validation->shouldHaveType('\Eadrax\Eadrax\Entity\Validation');
    }

    function it_catches_authorisation_exceptions_during_usecase_execution($data_user, $data_project, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->beConstructedWith($data_user, $data_project, $repository, $entity_auth, $entity_validation);

        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('Please login before you can add a new project.')
            )
        ));
    }

    function it_catches_validation_exceptions_during_usecase_execution($data_user, $data_project, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->get_user()->willReturn($data_user);
        $entity_auth->logged_in()->willReturn(TRUE);
        $entity_validation->errors()->willReturn(array('foo'));
        $entity_validation->check()->willReturn(FALSE);
        $this->beConstructedWith($data_user, $data_project, $repository, $entity_auth, $entity_validation);

        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_succesfully($data_user, $data_project, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->get_user()->willReturn($data_user);
        $entity_auth->logged_in()->willReturn(TRUE);
        $entity_validation->check()->willReturn(TRUE);
        $this->beConstructedWith($data_user, $data_project, $repository, $entity_auth, $entity_validation);
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
