<?php

namespace spec\Eadrax\Core\Usecase\User\Login;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase;

class Guest extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Login\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Validator $validator
     */
    function let($user, $repository, $authenticator, $validator)
    {
        $user->username = 'username';
        $user->password = 'password';

        $this->beConstructedWith($user, $repository, $authenticator, $validator);

        $this->username->shouldBe('username');
        $this->password->shouldBe('password');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Login\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_error_if_logged_in($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_authorises_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise();
    }

    function it_checks_for_invalid_information($validator)
    {
        $validator->setup(array(
            'username' => 'username',
            'password' => 'password'
        ))->shouldBeCalled();
        $validator->rule('username', 'not_empty')->shouldBeCalled();
        $validator->rule('password', 'not_empty')->shouldBeCalled();
        $validator->callback('username', array($this, 'is_existing_account'), array('username', 'password'))->shouldBeCalled();

        $validator->check()->shouldBeCalled()->willReturn(FALSE);
        $validator->errors()->shouldBeCalled()->willReturn(array(
            'foo' => 'bar'
        ));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_allows_valid_information($validator)
    {
        $validator->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate();
    }

    function it_logs_the_user_in($authenticator)
    {
        $authenticator->login($this->username, $this->password)->shouldBeCalled()->willReturn('foo');
        $this->login();
        $this->id->shouldBe('foo');
    }

    function it_checks_for_existing_accounts($repository)
    {
        $repository->is_existing_account('username', 'password')->shouldBeCalled()->willReturn(TRUE);
        $this->is_existing_account('username', 'password')->shouldBe(TRUE);
    }
}
