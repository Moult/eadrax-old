<?php

namespace spec\Eadrax\Core\Context\User\Dashboard;

require_once 'spec/Eadrax/Core/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context;

class User extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Core\Data\User   $data_user
     * @param Eadrax\Core\Entity\Auth $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $data_user->username = 'username';
        $this->beConstructedWith($data_user);
        $this->get_username()->shouldBe('username');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\User\Dashboard\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_error_if_not_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->link(array('entity_auth' => $entity_auth));

        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_dashboard();
    }

    function it_returns_with_a_users_username_if_logged_in($data_user, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $data_user->username = 'username';
        $entity_auth->get_user()->willReturn($data_user);
        $this->link(array('entity_auth' => $entity_auth));

        $this->authorise_dashboard()->shouldReturn(array(
            'username' => 'username'
        ));
    }
}
