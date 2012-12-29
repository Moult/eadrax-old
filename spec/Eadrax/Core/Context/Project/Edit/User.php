<?php

namespace spec\Eadrax\Core\Context\Project\Edit;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Interaction;

class User extends ObjectBehavior
{
    use Interaction;

    /**
     * @param \Eadrax\Core\Data\User   $data_user
     * @param \Eadrax\Core\Entity\Auth $entity_auth
     */
    function let($data_user, $entity_auth)
    {
        $this->beConstructedWith($data_user, $entity_auth);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('\Eadrax\Core\Context\Project\Edit\User');
    }

    function it_does_not_authorise_guests($entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    function it_authorises_logged_in_users($entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    function it_does_not_authorise_users_who_do_not_own_the_project($data_user, $entity_auth)
    {
        $data_user->get_id()->shouldBeCalled()->willReturn('24');
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringCheck_proposal_author();
    }

    function it_authorises_users_who_own_the_project($data_user, $entity_auth)
    {
        $data_user->id = '24';
        $data_user->get_id()->shouldBeCalled()->willReturn('24');
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringCheck_proposal_author();
    }
}
