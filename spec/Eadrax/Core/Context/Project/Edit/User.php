<?php

namespace spec\Eadrax\Core\Context\Project\Edit;

use PHPSpec2\ObjectBehavior;
use spec\Eadrax\Core\Context\Interaction;

class User extends ObjectBehavior
{
    use Interaction;

    /**
     * @param \Eadrax\Core\Data\User                     $data_user
     * @param \Eadrax\Core\Context\Project\Edit\Proposal $proposal
     * @param \Eadrax\Core\Entity\Auth                   $entity_auth
     */
    function let($data_user)
    {
        $data_user->username = 'foo';
        $this->beConstructedWith($data_user);
        $this->get_username()->shouldBe('foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('\Eadrax\Core\Context\Project\Edit\User');
    }

    function it_does_not_authorise_guests($entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->link(array('entity_auth' => $entity_auth));
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    /**
     * @param \Eadrax\Core\Data\User $author
     */
    function it_does_not_authorise_users_who_do_not_own_the_project($data_user, $author, $proposal, $entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $data_user->get_id()->shouldBeCalled()->willReturn('42');
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $author->get_id()->shouldBeCalled()->willReturn('24');
        $proposal->get_author()->shouldBeCalled()->willReturn($author);
        $this->link(array('proposal' => $proposal, 'entity_auth' => $entity_auth));
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }

    function it_authorises_users_who_own_the_project($data_user, $author, $proposal, $entity_auth)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $data_user->get_id()->shouldBeCalled()->willReturn('42');
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $author->get_id()->shouldBeCalled()->willReturn('42');
        $proposal->get_author()->shouldBeCalled()->willReturn($author);
        $proposal->set_author($this)->shouldBeCalled();
        $this->link(array('proposal' => $proposal, 'entity_auth' => $entity_auth));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_edit();
    }
}
