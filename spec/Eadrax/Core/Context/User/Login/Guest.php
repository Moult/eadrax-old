<?php

namespace spec\Eadrax\Core\Context\User\Login;

require_once 'spec/Eadrax/Core/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context;

class Guest extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Core\Data\User                     $data_user
     * @param Eadrax\Core\Context\User\Login\Repository $repository
     * @param Eadrax\Core\Entity\Auth                   $entity_auth
     * @param Eadrax\Core\Entity\Validation             $entity_validation
     */
    function let($data_user, $repository, $entity_auth, $entity_validation)
    {
        $data_user->username = 'username';
        $data_user->password = 'password';
        $this->beConstructedWith($data_user);
        $this->get_username()->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Login\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_error_if_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('entity_auth' => $entity_auth));
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_login();
    }

    function it_authorises_guests($entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->link(array('entity_auth' => $entity_auth));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_login();
    }

    function it_checks_for_invalid_information($entity_validation)
    {
        $entity_validation->setup(array(
            'username' => 'username',
            'password' => 'password'
        ))->shouldBeCalled();
        $entity_validation->rule('username', 'not_empty')->shouldBeCalled();
        $entity_validation->callback('username', array($this, 'is_existing_account'), array('username', 'password'))->shouldBeCalled();

        $entity_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $entity_validation->errors()->shouldBeCalled()->willReturn(array(
            'foo' => 'bar'
        ));
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_allows_valid_information($entity_validation)
    {
        $entity_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('entity_validation' => $entity_validation));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_logs_the_user_in($entity_auth)
    {
        $entity_auth->login($this->username, $this->password)->shouldBeCalled()->willReturn('foo');
        $this->link(array('entity_auth' => $entity_auth));
        $this->login()->shouldReturn('foo');
    }

    function it_checks_for_existing_accounts($repository)
    {
        $repository->is_existing_account('username', 'password')->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('repository' => $repository));
        $this->is_existing_account('username', 'password')->shouldBe(TRUE);
    }
}
