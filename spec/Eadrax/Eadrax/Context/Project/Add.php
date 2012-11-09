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
     * @param \Eadrax\Eadrax\Context\Project\Add\User       $role_user
     * @param \Eadrax\Eadrax\Data\Project                   $data_project
     * @param \Eadrax\Eadrax\Context\Project\Add\Proposal   $role_proposal
     * @param \Eadrax\Eadrax\Context\Project\Add\Repository $repository
     * @param \Eadrax\Eadrax\Entity\Auth                    $entity_auth
     * @param \Eadrax\Eadrax\Entity\Validation              $entity_validation
     */
    function let($data_user, $role_user, $data_project, $role_proposal, $repository, $entity_auth, $entity_validation)
    {
        $role_user->assign_data($data_user)->shouldBeCalled();
        $role_proposal->assign_data($data_project)->shouldBeCalled();
        $role_user->link(array(
            'proposal' => $role_proposal,
            'entity_auth' => $entity_auth
        ))->shouldBeCalled();
        $role_proposal->link(array(
            'repository' => $repository,
            'entity_validation' => $entity_validation
        ))->shouldBeCalled();
        $this->beConstructedWith($data_user, $role_user, $data_project, $role_proposal, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Project\Add');
    }

    function it_should_assign_roles_to_datas()
    {
        $this->user->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\User');
        $this->proposal->shouldHaveType('\Eadrax\Eadrax\Context\Project\Add\Proposal');
    }

    function it_catches_authorisation_exceptions_during_usecase_execution($role_user)
    {
        $role_user->authorise_project_add()->shouldBeCalled()->willThrow('\Eadrax\Eadrax\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_catches_validation_exceptions_during_usecase_execution($role_user)
    {
        $role_user->authorise_project_add()->shouldBeCalled()->willThrow('\Eadrax\Eadrax\Exception\Validation', array('foo'));
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_succesfully($role_user)
    {
        $role_user->authorise_project_add()->shouldBeCalled();
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
