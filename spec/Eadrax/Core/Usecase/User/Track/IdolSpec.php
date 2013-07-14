<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PhpSpec\ObjectBehavior;

class IdolSpec extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Usecase\User\Track\Repository $repository
     * @param Eadrax\Core\Tool\Emailer $emailer
     * @param Eadrax\Core\Tool\Formatter $formatter
     */
    function let($user, $repository, $emailer, $formatter)
    {
        $user->id = 'idol_id';
        $this->beConstructedWith($user, $repository, $emailer, $formatter);

    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Idol');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    function it_can_get_id()
    {
        $this->get_id()->shouldReturn('idol_id');
    }

    function it_can_notify_about_new_fan($repository, $emailer, $formatter)
    {
        $repository->get_username_and_email('idol_id')->willReturn(array('idol_username', 'idol_email'));
        $repository->get_username('fan_id')->willReturn('fan_username');
        $formatter->setup(array(
            'idol_username' => 'idol_username',
            'fan_id' => 'fan_id',
            'fan_username' => 'fan_username'
        ))->shouldBeCalled();
        $formatter->format('email_user_track_body')->shouldBeCalled()->willReturn('email_body');
        $formatter->format('email_user_track_subject')->shouldBeCalled()->willReturn('email_subject');
        $emailer->set_to('idol_email')->shouldBeCalled();
        $emailer->set_subject('email_subject')->shouldBeCalled();
        $emailer->set_body('email_body')->shouldBeCalled();
        $emailer->send()->shouldBeCalled();
        $this->notify_new_fan('fan_id');
    }
}
