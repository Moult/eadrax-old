<?php

namespace spec\Eadrax\Core\Usecase\Project\Add;

use PHPSpec2\ObjectBehavior;

class User extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $data_user
     * @param Eadrax\Core\Tool\Auth $tool_auth
     */
    function let($data_user, $tool_auth)
    {
        $data_user->id = 'foo';
        $this->beConstructedWith($data_user, $tool_auth);
        $this->id->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Add\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_does_not_authorise_guests($tool_auth)
    {
        $tool_auth->logged_in()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_add();
    }

    function it_checks_the_authorised_user($tool_auth)
    {
        $tool_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_add();
    }
}
