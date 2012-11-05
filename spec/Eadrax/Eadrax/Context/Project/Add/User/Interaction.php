<?php

namespace spec\Eadrax\Eadrax\Context\Project\Add\User;

trait Interaction
{
    function it_throws_an_authorisation_exception_if_not_logged_in($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Eadrax\Exception\Authorisation')->duringAuthorise_project_add();
    }

    function it_continues_to_load_authentication_details_and_assign_to_proposal_if_logged_in($entity_auth, $role_proposal)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);

        $model_user = new \Eadrax\Eadrax\Model\User;
        $model_user->username = 'foo';
        $model_user->id = 'bar';
        $entity_auth->get_user()->shouldBeCalled()->willReturn($model_user);

        $role_proposal->assign_author($this)->willReturn('foobar');
        $this->authorise_project_add()->shouldReturn('foobar');

        $this->get_username()->shouldBe('foo');
        $this->get_id()->shouldBe('bar');
    }
}
