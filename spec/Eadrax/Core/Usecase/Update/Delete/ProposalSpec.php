<?php

namespace spec\Eadrax\Core\Usecase\Update\Delete;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProposalSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Update $update
     * @param Eadrax\Core\Usecase\Update\Delete\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     */
    function let($update, $repository, $authenticator)
    {
        $update->id = 'update_id';
        $repository->get_author_id('update_id')->shouldBeCalled()->willReturn('author_id');
        $this->beConstructedWith($update, $repository, $authenticator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Delete\Proposal');
    }

    /**
     * @param Eadrax\Core\Data\User $logged_in_user
     */
    function it_should_authorise_update_owners($logged_in_user, $authenticator)
    {
        $logged_in_user->id = 'impostor_id';
        $authenticator->get_user()->shouldBeCalled()->willReturn($logged_in_user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_can_delete($repository)
    {
        $repository->purge_files('update_id')->shouldBeCalled();
        $repository->delete_update('update_id')->shouldBeCalled();
        $this->delete();
    }
}
