<?php

namespace spec\Eadrax\Eadrax\Context\User;

require_once 'spec/Eadrax/Eadrax/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context\Core;

class Login extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Eadrax\Model\User                    $model_user
     * @param Eadrax\Eadrax\Context\User\Login\Guest      $role_guest
     * @param Eadrax\Eadrax\Context\User\Login\Repository $repository
     * @param Eadrax\Eadrax\Entity\Auth                   $entity_auth
     * @param Eadrax\Eadrax\Entity\Validation             $entity_validation
     */
    function let($model_user, $role_guest, $repository, $entity_auth, $entity_validation)
    {
        $role_guest->assign_data($model_user)->shouldBeCalled();
        $role_guest->link(array(
            'repository' => $repository,
            'entity_auth' => $entity_auth,
            'entity_validation' => $entity_validation
        ))->shouldBeCalled();
        $this->beConstructedWith($model_user, $role_guest, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Login');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Eadrax\Context\Core');
    }

    function it_catches_authorisation_exceptions_during_usecase($role_guest)
    {
        $role_guest->authorise_login()->willThrow('Eadrax\Eadrax\Exception\Authorisation', 'foo');
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
        $role_guest->authorise_login()->willThrow('Eadrax\Eadrax\Exception\Validation', array('foo'));
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
        $role_guest->authorise_login()->willReturn('foo');
        $this->execute()->shouldBe(array(
            'status' => 'success'
        ));
    }
}
