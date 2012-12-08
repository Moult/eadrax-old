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
    function let($data_user)
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

    function it_does_not_authorise_guests($entity_auth)
    {
        $entity_auth->logged_in()->willReturn(FALSE);
        $this->link(array('entity_auth' => $entity_auth));
        $this->shouldThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_add();
    }

    function it_checks_and_loads_the_authorised_user($data_user, $entity_auth, $proposal)
    {
        $entity_auth->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $entity_auth->get_user()->shouldBeCalled()->willReturn($data_user);
        $proposal->set_author($this)->shouldBeCalled();
        $this->link(array('entity_auth' => $entity_auth, 'proposal' => $proposal));
        $this->shouldNotThrow('\Eadrax\Core\Exception\Authorisation')->duringAuthorise_project_add();
    }
}
