<?php

namespace spec\Eadrax\Core\Usecase\Kudos\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NominationSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\Kudos\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($update, $user, $repository, $authenticator)
    {
        $update->id = 'id';
        $user->id = 'user_id';
        $authenticator->get_user()->willReturn($user);
        $this->beConstructedWith($update, $repository, $authenticator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Kudos\Delete\Nomination');
    }

    function it_is_an_update()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Update');
    }

    function it_can_check_whether_or_not_it_has_a_kudos($repository)
    {
        $repository->does_update_have_kudos('id', 'user_id')->shouldBeCalled()->willReturn(TRUE);
        $this->has_kudos()->shouldReturn(TRUE);
    }

    function it_can_delete_kudos($repository)
    {
        $repository->delete_kudos_from_update('user_id', 'id')->shouldBeCalled();
        $this->delete_kudos();
    }
}
