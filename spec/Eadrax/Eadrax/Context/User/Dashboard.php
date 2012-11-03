<?php

namespace spec\Eadrax\Eadrax\Context\User;

require_once 'spec/Eadrax/Eadrax/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Eadrax\Context\Core;

class Dashboard extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Eadrax\Model\User                  $model_user
     * @param Eadrax\Eadrax\Context\User\Dashboard\User $role_user
     * @param Eadrax\Eadrax\Entity\Auth                 $entity_auth
     */
    function let($model_user, $role_user, $entity_auth)
    {
        throw new \PHPSpec2\Exception\Example\PendingException('Waiting for bug #62');
        $role_user->assign_data($model_user)->shouldBeCalled();
        // TODO: This is throwing errors due to a PHPSpec2 bug.
        // @link https://github.com/phpspec/phpspec2/issues/62
        $role_user->link(array(
            'entity_auth' => $entity_auth
        ))->shouldBeCalled();
        $this->beConstructedWith($model_user, $role_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        throw new \PHPSpec2\Exception\Example\PendingException('Waiting for bug #62');
        $this->shouldHaveType('Eadrax\Eadrax\Context\User\Dashboard');
    }

    function it_should_be_a_context()
    {
        throw new \PHPSpec2\Exception\Example\PendingException('Waiting for bug #62');
        $this->shouldHaveType('Eadrax\Eadrax\Context\Core');
    }

    function it_catches_authorisation_exceptions_during_usecase($role_user)
    {
        throw new \PHPSpec2\Exception\Example\PendingException('Waiting for bug #62');
        $role_user->authorise_dashboard()->willThrow('Eadrax\Eadrax\Exception\Authorisation', 'foo');
        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_successfully($role_user)
    {
        throw new \PHPSpec2\Exception\Example\PendingException('Waiting for bug #62');
        $role_user->authorise_dashboard()->willReturn('foo');
        $this->execute()->shouldBe(array(
            'status' => 'success',
            'data' => 'foo'
        ));
    }
}
