<?php

namespace spec\Eadrax\Core\Context\Project\Edit;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Context\Project\Edit\User $user
     */
    function let($user)
    {
        $this->beConstructedWith($user);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Edit\Interactor');
    }

    function it_runs_the_interaction_chain($user)
    {
        $user->authorise_project_edit()->shouldBeCalled();
        $user->check_proposal_author()->shouldBeCalled();
        $this->interact();
    }

    function it_executes_the_usecase_succesfully()
    {
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }

    function it_catches_authentication_errors_during_usecase_execution($user)
    {
        $user->authorise_project_edit()->shouldBeCalled()->willThrow('Eadrax\Core\Exception\Authorisation', 'foo');
        $this->execute()->shouldReturn(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }
}
