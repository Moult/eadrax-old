<?php

namespace spec\Eadrax\Eadrax\Context\User\Register\Guest;

trait Interaction
{
    function it_throws_an_authorisation_error_if_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Eadrax\Exception\Authorisation')->duringAuthorise_registration();
    }

    function it_proceeds_to_validate_information_if_not_logged_in($entity_auth, $entity_validation)
    {
        $entity_auth->logged_in()->willReturn(FALSE);

        $entity_validation->setup(array(
            'username' => 'username',
            'password' => '',
            'email' => ''
        ))->shouldBeCalled();
        $entity_validation->rule('username', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('username', 'regex', '/^[a-z_.]++$/iD')->shouldBeCalled();
        $entity_validation->rule('username', 'min_length', '4')->shouldBeCalled();
        $entity_validation->rule('username', 'max_length', '15')->shouldBeCalled();
        $entity_validation->callback('username', array($this, 'is_unique_username'), array($this->username))->shouldBeCalled();
        $entity_validation->rule('password', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('password', 'min_length', '6')->shouldBeCalled();
        $entity_validation->rule('email', 'not_empty')->shouldBeCalled();
        $entity_validation->rule('email', 'email')->shouldBeCalled();

        $entity_validation->check()->willReturn(FALSE);
        $entity_validation->errors()->willReturn(array(
            'foo' => 'bar'
        ));

        $this->shouldThrow('\Eadrax\Eadrax\Exception\Validation')->duringAuthorise_registration();
    }

    function it_proceeds_to_register_and_login_if_validation_succeeds($repository, $entity_auth, $entity_validation)
    {
        $entity_validation->check()->willReturn(TRUE);

        $repository->register($this)->shouldBeCalled();
        $entity_auth->login($this->username, $this->password)->shouldBeCalled()->willReturn('foo');
        $this->validate_information()->shouldReturn('foo');
    }

    function it_checks_the_repository_for_unique_usernames($repository)
    {
        $repository->is_unique_username('foo')->shouldBeCalled()->willReturn(TRUE);
        $this->is_unique_username('foo')->shouldReturn(TRUE);
    }
}
