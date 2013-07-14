<?php

namespace spec\Eadrax\Core\Usecase\Project\Track\User;

use PhpSpec\ObjectBehavior;

class TrackSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Track\Repository $user_track
     * @param Eadrax\Core\Tool\Authenticator $authenticator
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($user, $user_track, $authenticator, $emailer, $formatter)
    {
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
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\User\Track');
    }

    function it_can_set_author_id()
    {
        $this->set_author_id(42);
        $this->data['user']->id->shouldBe(42);
    }

    /**
     * @param Eadrax\Core\Data\User $user_details
     */
    function it_fetches_the_user_track_interactor($user, $user_details, $user_track, $authenticator)
    {
        $user->id = 'id';
        $user_track->get_username_and_email('id')->willReturn($user_details);
        $authenticator->get_user()->willReturn($user);
        $this->fetch()->shouldHaveType('Eadrax\Core\Usecase\User\Track\Interactor');
    }
}
