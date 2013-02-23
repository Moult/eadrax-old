<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Add\Proposal $proposal
     * @param Eadrax\Core\Usecase\Project\Add\User $user
     * @param Eadrax\Core\Usecase\Project\Prepare\Interactor $project_prepare
     */
    function let($proposal, $user, $project_prepare)
    {
        $this->beConstructedWith($proposal, $user, $project_prepare);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add\Interactor');
    }

    function it_carries_out_the_interaction_chain($proposal, $user, $project_prepare)
    {
        $user->authorise_project_add()->shouldBeCalled();
        $project_prepare->interact()->shouldBeCalled();
        $proposal->submit()->shouldBeCalled();
        $this->interact();
    }

    function it_catches_authorisation_errors_during_usecase_execution($user)
    {
        $user->authorise_project_add()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_catches_validation_errors_during_usecase_execution($project_prepare)
    {
        $project_prepare->interact()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Validation', array('foo' => 'bar'));
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo' => 'bar')
            )
        ));
    }

    function it_executes_the_usecase_successfully()
    {
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
