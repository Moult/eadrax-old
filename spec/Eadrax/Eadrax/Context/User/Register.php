<?php

namespace spec\Eadrax\Eadrax\Context\User;

require_once 'spec/Eadrax/Eadrax/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context\Core;

class Register extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Eadrax\Data\User                       $data_user
     * @param Eadrax\Eadrax\Context\User\Register\Guest      $role_guest
     * @param Eadrax\Eadrax\Context\User\Register\Repository $repository
     * @param Eadrax\Eadrax\Entity\Auth                      $entity_auth
     */
    function let($data_user, $role_guest, $repository, $entity_auth)
    {
        $role_guest->assign_data($data_user)->shouldBeCalled();
        $role_guest->link(array(
            'repository' => $repository,
            'entity_auth' => $entity_auth
        ))->shouldBeCalled();
        $this->beConstructedWith($data_user, $role_guest, $repository, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Register');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Core');
    }

    function it_catches_authorisation_exceptions_during_usecase($role_guest)
    {
        $role_guest->authorise_registration()->willThrow('Eadrax\Eadrax\Exception\Authorisation', 'foo');
        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_catches_validation_exceptions_during_usecase($role_guest)
    {
        $role_guest->authorise_registration()->willThrow('Eadrax\Eadrax\Exception\Validation', array('foo'));
        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_successfully($role_guest)
    {
        $role_guest->authorise_registration()->willReturn('foo');
        $this->execute()->shouldBe(array(
            'status' => 'success'
        ));
    }
}
