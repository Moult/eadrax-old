<?php

namespace spec\Eadrax\Core\Usecase\User\Track;

use PHPSpec2\ObjectBehavior;

class Idol extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $user_details
     * @param Eadrax\Core\Usecase\User\Track\Repository $repository
     * @param Eadrax\Core\Tool\Mail $mail
     */
    function let($user, $user_details, $repository, $mail)
    {
        $user->id = 'id';
        $user_details->username = 'Foobar';
        $user_details->email = 'foo@bar.com';

        $repository->get_username_and_email('id')->shouldBeCalled()->willReturn($user_details);
        $this->beConstructedWith($user, $repository, $mail);

        $this->id->shouldBe('id');
        $this->username->shouldBe('Foobar');
        $this->email->shouldBe('foo@bar.com');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\User\Track\Idol');
    }

    function it_should_be_a_user_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\User');
    }

    /**
     * @param Eadrax\Core\Usecase\User\Track\User $fan
     */
    function it_sends_notification_messages($fan, $mail)
    {
        $fan->username = 'Barfoo';
        $message = <<<EOT
Hey Foobar,

Barfoo is now a new fan of your work on WIPUP! They'll be notified whenever you make a new update to any of your projects.

Keep up the great work, stay ambitious, and create like no tomorrow!

Cheers,
The WIPUP Team
EOT;
        $mail->send(
            'foo@bar.com',
            'noreply@wipup.org',
            'You have a new fan on WIPUP!',
            $message
        )->shouldBeCalled();
        $this->notify_new_fan($fan);
    }
}
