<?php

namespace spec\Eadrax\Core\Usecase\User;

use PhpSpec\ObjectBehavior;

class TrackSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $user_details
     * @param Eadrax\Core\Usecase\User\Track\Repository $user_track
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($user, $user_details, $user_track, $authenticator, $emailer, $formatter)
    {
        $user->id = 'id';
        $user_track->get_username_and_email('id')->willReturn($user_details);
        $authenticator->get_user()->willReturn($user);

        $data = array(
            'user' => $user
        );

        $repositories = array(
            'user_track' => $user_track
        );

        $tools = array(
            'authenticator' => $authenticator,
            'emailer' => $emailer,
            'formatter' => $formatter
        );

        $this->beConstructedWith($data, $repositories, $tools);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track');
    }

    function it_fetches_the_interactor()
    {
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Track\Interactor');
    }
}
