<?php

namespace spec\Eadrax\Core\Usecase\User\Register;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase;

class Registrant extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Register\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($user, $repository, $authenticator, $validator)
    {
        $user->username = 'username';
        $user->password = 'password';
        $user->password_verify = 'password_verify';
        $user->email = 'email';
        $this->beConstructedWith($user, $repository, $authenticator, $validator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Register\Registrant');
    }

    function it_is_a_registrant_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_does_not_authorise_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_authorises_the_registrant_if_not_logged_in($authenticator)
    {
        $authenticator->logged_in()->willReturn(FALSE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_checks_for_invalid_user_information($validator)
    {
        $validator->setup(array(
            'username' => 'username',
            'password' => 'password',
            'password_verify' => 'password_verify',
            'email' => 'email'
        ))->shouldBeCalled();
        $validator->rule('username', 'not_empty')->shouldBeCalled();
        $validator->rule('username', 'regex', '/^[a-z_.]++$/iD')->shouldBeCalled();
        $validator->rule('username', 'min_length', '3')->shouldBeCalled();
        $validator->rule('username', 'max_length', '15')->shouldBeCalled();
        $validator->callback('username', array($this, 'is_unique_username'), array('username'))->shouldBeCalled();
        $validator->rule('password', 'not_empty')->shouldBeCalled();
        $validator->rule('password', 'min_length', '6')->shouldBeCalled();
        $validator->rule('password', 'matches', 'password_verify')->shouldBeCalled();
        $validator->rule('email', 'not_empty')->shouldBeCalled();
        $validator->rule('email', 'email')->shouldBeCalled();

        $validator->check()->willReturn(FALSE);
        $validator->errors()->willReturn(array(
            'foo' => 'bar'
        ));

        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_validates_valid_user_information($validator)
    {
        $validator->check()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_registers_the_user($repository)
    {
        $repository->register('username', 'password', 'email')->shouldBeCalled();
        $this->register();
    }

    function it_checks_the_repository_for_unique_usernames($repository)
    {
        $repository->is_unique_username('foo')->shouldBeCalled()->willReturn(TRUE);
        $this->is_unique_username('foo')->shouldReturn(TRUE);
    }
}
