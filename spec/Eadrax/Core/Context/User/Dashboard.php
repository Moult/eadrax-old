<?php

namespace spec\Eadrax\Core\Context\User;

require_once 'spec/Eadrax/Core/Context/Core.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Core;

class Dashboard extends ObjectBehavior
{
    use Core;

    /**
     * @param Eadrax\Core\Data\User                   $data_user
     * @param Eadrax\Core\Entity\Auth                 $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $this->beConstructedWith($data_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Dashboard');
    }

    function it_should_be_a_context()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Core');
    }

    function it_assigns_data_to_roles()
    {
        $this->user->shouldHaveType('Eadrax\Core\Context\User\Dashboard\User');
        $this->user->shouldHaveType('Eadrax\Core\Data\User');
        $this->user->entity_auth->shouldHaveType('Eadrax\Core\Entity\Auth');
    }

    function it_catches_authorisation_exceptions_during_usecase($data_user, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->beConstructedWith($data_user, $entity_auth);

        $this->execute()->shouldBe(array(
            'status' => 'failure',
            'type' => 'authorisation',
            'data' => array(
                'errors' => array('Please login before you can view your dashboard.')
            )
        ));
    }

    function it_executes_the_usecase_successfully($data_user, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $data_user->username = 'Foo';
        $entity_auth->get_user()->willReturn($data_user);
        $this->beConstructedWith($data_user, $entity_auth);

        $this->execute()->shouldBe(array(
            'status' => 'success',
            'data' => array('username' => 'Foo')
        ));
    }
}
