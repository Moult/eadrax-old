<?php

namespace spec\Eadrax\Core\Context\Project\Add;

require_once 'spec/Eadrax/Core/Context/Interaction.php';

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context;

class User extends ObjectBehavior
{
    use Context\Interaction;

    /**
     * @param Eadrax\Core\Data\User                    $data_user
     * @param Eadrax\Core\Context\Project\Add\Proposal $proposal
     * @param Eadrax\Core\Entity\Auth                  $entity_auth
     */
    function let($data_user, $proposal, $entity_auth)
    {
        $data_user->id = 'foo';
        $this->beConstructedWith($data_user);
        $this->get_id()->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Context\Project\Add\User');
    }

    function it_is_a_user_role()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_throws_an_authorisation_exception_if_not_logged_in($data_user, $proposal, $entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->link(array('entity_auth' => $entity_auth));

        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_add();
    }

    function it_continues_to_load_authentication_details_and_assign_to_proposal_if_logged_in($data_user, $entity_auth, $proposal)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);

        $data_user->username = 'foo';
        $data_user->id = 'bar';
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $proposal->assign_author($this)->willReturn('foobar');
        $this->link(array('entity_auth' => $entity_auth, 'proposal' => $proposal));

        $this->authorise_project_add()->shouldReturn('foobar');

        $this->get_username()->shouldBe('foo');
        $this->get_id()->shouldBe('bar');
    }
}
