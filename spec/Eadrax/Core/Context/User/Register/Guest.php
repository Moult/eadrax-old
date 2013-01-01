<?php

namespace spec\Eadrax\Core\Context\User\Register;

require_once 'spec/Eadrax/Core/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context;

class Guest extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Core\Data\User                        $data_user
     * @param Eadrax\Core\Context\User\Register\Repository $repository
     * @param Eadrax\Core\Entity\Auth                      $entity_auth
     * @param Eadrax\Core\Entity\Validation                $entity_validation
     */
    function let($data_user, $repository, $entity_auth, $entity_validation)
    {
        $data_user->username = 'username';
        $this->beConstructedWith($data_user, $repository, $entity_auth, $entity_validation);
        $this->get_username()->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Register\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_does_not_authorise_logged_in_users($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_registration();
    }

    function it_authorises_guest_users($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_registration();
    }

    function it_checks_for_invalid_user_information($entity_validation)
    {
        $entity_validation->setup(array(
            'username' => 'username',
            'password' => '',
            'email' => ''
        ))->shouldBeCalled();
        $entity_validation->rule('username', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('username', 'regex', '/^[a-z_.]++$/iD')->shouldBeCalled();
        $entity_validation->rule('username', 'min_length', '4')->shouldBeCalled();
        $entity_validation->rule('username', 'max_length', '15')->shouldBeCalled();
        $entity_validation->callback('username', array($this, 'is_unique_username'), array('username'))->shouldBeCalled();
        $entity_validation->rule('password', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('password', 'min_length', '6')->shouldBeCalled();
        $entity_validation->rule('email', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('email', 'email')->shouldBeCalled();

        $entity_validation->check()->willReturn(FALSE);
        $entity_validation->errors()->willReturn(array(
            'foo' => 'bar'
        ));

        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_validates_valid_user_information($entity_validation)
    {
        $entity_validation->check()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_registers_the_user($repository)
    {
        $repository->register($this)->shouldBeCalled();
        $this->register();
    }

    function it_checks_the_repository_for_unique_usernames($repository)
    {
        $repository->is_unique_username('foo')->shouldBeCalled()->willReturn(TRUE);
        $this->is_unique_username('foo')->shouldReturn(TRUE);
    }
}
