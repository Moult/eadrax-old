<?php

namespace spec\Eadrax\Eadrax\Context\User\Dashboard\User;

trait Interaction
{
    function it_throws_an_authorisation_error_if_not_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Eadrax\Exception\Authorisation')->duringAuthorise_dashboard();
    }

    function it_returns_with_a_users_username_if_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(TRUE);
        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->set_username('username');
        $entity_auth->get_user()->willReturn($model_user);
        $this->authorise_dashboard()->shouldReturn(array(
            'username' => 'username'
        ));
    }
}
