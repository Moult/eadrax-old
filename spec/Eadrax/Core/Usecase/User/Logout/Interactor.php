<?php

namespace spec\Eadrax\Core\Usecase\User\Logout;

use PHPSpec2\ObjectBehavior;

class Interactor extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Entity\Auth $entity_auth
     */
    function let($entity_auth)
    {
        $this->beConstructedWith($entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Logout\Interactor');
    }

    function it_runs_the_interaction_chain($entity_auth)
    {
        $entity_auth->logout()->shouldBeCalled();
        $this->interact();
    }

    function it_executes_the_usecase_succesfully($entity_auth)
    {
        $entity_auth->logout()->shouldBeCalled();
        $this->execute()->shouldReturn(array(
            'status' => 'success'
        ));
    }
}
