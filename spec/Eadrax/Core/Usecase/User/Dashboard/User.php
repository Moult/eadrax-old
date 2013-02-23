<?php

namespace spec\Eadrax\Core\Usecase\User\Dashboard;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Usecase;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User   $data_user
     * @param Eadrax\Core\Tool\Auth $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $data_user->username = 'username';
        $this->beConstructedWith($data_user, $entity_auth);
        $this->get_username()->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Dashboard\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_error_if_not_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);

        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_dashboard();
    }

    function it_returns_with_a_users_username_if_logged_in($data_user, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $data_user->get_username->willReturn('username');
        $entity_auth->get_user()->willReturn($data_user);

        $this->authorise_dashboard()->shouldReturn(array(
            'username' => 'username'
        ));
    }
}
