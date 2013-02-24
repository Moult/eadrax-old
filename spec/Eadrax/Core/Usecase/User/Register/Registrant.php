<?php

namespace spec\Eadrax\Core\Usecase\User\Register;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase;

class Registrant extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Register\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Validation $validation
     */
    function let($user, $repository, $auth, $validation)
    {
        $user->username = 'username';
        $this->beConstructedWith($user, $repository, $auth, $validation);
        $this->username->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Register\Registrant');
    }

    function it_is_a_registrant_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_does_not_authorise_logged_in_users($auth)
    {
        $auth->logged_in()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_authorises_the_registrant_if_not_logged_in($auth)
    {
        $auth->logged_in()->willReturn(FALSE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_checks_for_invalid_user_information($validation)
    {
        $validation->setup(array(
            'username' => 'username',
            'password' => '',
            'email' => ''
        ))->shouldBeCalled();
        $validation->rule('username', 'not_empty')->shouldBeCalled();
        $validation->rule('username', 'regex', '/^[a-z_.]++$/iD')->shouldBeCalled();
        $validation->rule('username', 'min_length', '3')->shouldBeCalled();
        $validation->rule('username', 'max_length', '15')->shouldBeCalled();
        $validation->callback('username', array($this, 'is_unique_username'), array('username'))->shouldBeCalled();
        $validation->rule('password', 'not_empty')->shouldBeCalled();
        $validation->rule('password', 'min_length', '6')->shouldBeCalled();
        $validation->rule('email', 'not_empty')->shouldBeCalled();
        $validation->rule('email', 'email')->shouldBeCalled();

        $validation->check()->willReturn(FALSE);
        $validation->errors()->willReturn(array(
            'foo' => 'bar'
        ));

        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_validates_valid_user_information($validation)
    {
        $validation->check()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
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
