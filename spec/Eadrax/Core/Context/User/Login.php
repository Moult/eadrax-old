<?php

namespace spec\Eadrax\Core\Context\User;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Login extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User                    $data_user
     * @param Eadrax\Core\Context\User\Login\Repository $repository
     * @param Eadrax\Core\Entity\Auth                   $entity_auth
     * @param Eadrax\Core\Entity\Validation             $entity_validation
     */
    function let($data_user, $repository, $entity_auth, $entity_validation)
    {
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Login');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Core');
    }

    function it_assigns_data_to_roles()
    {
        $this->guest->shouldHaveType('Eadrax\Core\Context\User\Login\Guest');
        $this->guest->shouldHaveType('Eadrax\Core\Data\User');
        $this->guest->repository->shouldHaveType('Eadrax\Core\Context\User\Login\Repository');
        $this->guest->entity_auth->shouldHaveType('Eadrax\Core\Entity\Auth');
        $this->guest->entity_validation->shouldHaveType('Eadrax\Core\Entity\Validation');
    }

    function it_catches_authorisation_exceptions_during_usecase($data_user, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);

        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('Logged in users don\'t need to login again.')
            )
        ));
    }

    function it_catches_validation_exceptions_during_usecase($data_user, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $entity_validation->errors()->willReturn(array('foo'));
        $entity_validation->check()->willReturn(FALSE);
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);

        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'validation',
            'data' => array(
                'errors' => array('foo')
            )
        ));
    }

    function it_executes_the_usecase_successfully($data_user, $repository, $entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $entity_validation->check()->willReturn(TRUE);
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);

        $this->execute()->shouldBe(array(
            'status' => 'success'
        ));
    }
}
