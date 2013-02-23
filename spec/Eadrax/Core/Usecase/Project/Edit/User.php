<?php

namespace spec\Eadrax\Core\Usecase\Project\Edit;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Tool\Auth $tool_auth
     */
    function let($data_user, $tool_auth)
    {
        $this->beConstructedWith($data_user, $tool_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('\Eadrax\Core\Usecase\Project\Edit\User');
    }

    function it_does_not_authorise_guests($tool_auth)
    {
        $tool_auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    function it_authorises_logged_in_users($tool_auth)
    {
        $tool_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    /**
     * @param Eadrax\Core\Data\user $data_impostor
     */
    function it_does_not_authorise_users_who_do_not_own_the_project($data_impostor, $data_user, $tool_auth)
    {
        $data_user->id = '24';
        $tool_auth->get_user()->shouldBeCalled()->willReturn($data_impostor);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringCheck_proposal_author();
    }

    function it_authorises_users_who_own_the_project($data_user, $tool_auth)
    {
        $data_user->id = '24';
        $tool_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringCheck_proposal_author();
    }
}
