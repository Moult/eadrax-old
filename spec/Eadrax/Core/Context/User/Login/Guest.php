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
        $entity_auth->logged_in()->willReturn(TRUE);
        $this->link(array('entity_auth' => $entity_auth));

        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_login();
    }

    function it_proceeds_to_validate_information_if_not_logged_in($entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(FALSE);

        $entity_validation->setup(array(
            'username' => 'username'
        ))->shouldBeCalled();
        $entity_validation->rule('username', 'not_empty')->shouldBeCalled();
        $entity_validation->callback('username', array($this, 'is_existing_account'), array($this->username, $this->password))->shouldBeCalled();

        $entity_validation->check()->willReturn(FALSE);
        $entity_validation->errors()->willReturn(array(
            'foo' => 'bar'
        ));
        $this->link(array('entity_auth' => $entity_auth, 'entity_validation' => $entity_validation));

        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringAuthorise_login();
    }

    function it_proceeds_to_login_if_validation_succeeds($entity_auth, $entity_validation)
    {
        $entity_validation->check()->willReturn(TRUE);

        $entity_auth->login($this->username, $this->password)->shouldBeCalled()->willReturn('foo');
        $this->link(array('entity_auth' => $entity_auth, 'entity_validation' => $entity_validation));
        $this->validate_information()->shouldReturn('foo');
    }

    function it_checks_the_repository_for_existing_accounts($repository)
    {
        $repository->is_existing_account('foo', 'bar')->shouldBeCalled()->willReturn(TRUE);
        $this->link(array('repository' => $repository));
        $this->is_existing_account('foo', 'bar')->shouldBe(TRUE);
    }

}
