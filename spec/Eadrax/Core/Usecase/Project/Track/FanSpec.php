<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PhpSpec\ObjectBehavior;

class FanSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Usecase\Project\Track\Repository $repository
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Data\User $fan
     */
    function let($repository, $authenticator, $fan)
    {
        $fan->id = 'fan_id';
        $authenticator->get_user()->willReturn($fan);
        $this->beConstructedWith($repository, $authenticator);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Fan');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_should_authorise_logged_in_users($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(TRUE);
        $this->authorise();
    }

    function it_should_not_authorise_guests($authenticator)
    {
        $authenticator->logged_in()->shouldBeCalled()->willReturn(FALSE);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    function it_finds_the_number_of_projects_owned_by_an_author($repository)
    {
        $repository->get_number_of_tracked_projects_owned_by_author('fan_id', 'author_id')->shouldBeCalled()->willReturn(42);
        $this->get_number_of_projects_owned_by_author('author_id')->shouldReturn(42);
    }
}
