<?php

namespace spec\Eadrax\Core\Usecase\User\Login;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase;

class Guest extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Usecase\User\Login\Repository $repository
     * @param Eadrax\Core\Tool\Auth $tool_auth
     * @param Eadrax\Core\Tool\Validation $tool_validation
     */
    function let($data_user, $repository, $tool_auth, $tool_validation)
    {
        $data_user->username = 'username';
        $data_user->password = 'password';
        $this->beConstructedWith($data_user, $repository, $tool_auth, $tool_validation);
        $this->username->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Login\Guest');
    }

    function it_is_a_guest_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_error_if_logged_in($tool_auth)
    {
        $tool_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_login();
    }

    function it_authorises_guests($tool_auth)
    {
        $tool_auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_login();
    }

    function it_checks_for_invalid_information($tool_validation)
    {
        $tool_validation->setup(array(
            'username' => 'username',
            'password' => 'password'
        ))->shouldBeCalled();
        $tool_validation->rule('username', 'not_empty')->shouldBeCalled();
        $tool_validation->callback('username', array($this, 'is_existing_account'), array('username', 'password'))->shouldBeCalled();

        $tool_validation->check()->shouldBeCalled()->willReturn(FALSE);
        $tool_validation->errors()->shouldBeCalled()->willReturn(array(
            'foo' => 'bar'
        ));
        $this->shouldThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_allows_valid_information($tool_validation)
    {
        $tool_validation->check()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Validation')->duringValidate_information();
    }

    function it_logs_the_user_in($tool_auth)
    {
        $tool_auth->login($this->username, $this->password)->shouldBeCalled()->willReturn('foo');
        $this->login()->shouldReturn('foo');
    }

    function it_checks_for_existing_accounts($repository)
    {
        $repository->is_existing_account('username', 'password')->shouldBeCalled()->willReturn(TRUE);
        $this->is_existing_account('username', 'password')->shouldBe(TRUE);
    }
}
