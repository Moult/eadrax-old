<?php

namespace spec\Eadrax\Core\Usecase\Update\Add;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Usecase\Update\Add\Repository $repository
     * @param Eadrax\Core\Tool\Auth $auth
     * @param Eadrax\Core\Tool\Mail $mail
     */
    function let($author, $project, $repository, $auth, $mail)
    {
        $project->id = 'id';
        $author->id = '1';
        $author->username = 'Author';
        $auth->get_user()->willReturn($author);
        $this->beConstructedWith($project, $repository, $auth, $mail);
        $this->id->shouldBe('id');
        $this->author->shouldBe($author);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Update\Add\Project');
    }

    function it_should_be_a_project()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Data\User $author
     */
    function it_authorises_project_authors($author, $repository)
    {
        $author->id = '1';
        $repository->get_project_author($this)->shouldBeCalled()->willReturn($author);
        $this->authorise();
    }

    /**
     * @param Eadrax\Core\Data\User $user
     * @param Eadrax\Core\Data\User $author
     */
    function it_does_not_authorise_users_who_are_not_project_authors($user, $repository)
    {
        $user->id = '2';
        $repository->get_project_author($this)->shouldBeCalled()->willReturn($user);
        $this->shouldThrow('Eadrax\Core\Exception\Authorisation')
            ->duringAuthorise();
    }

    /**
     * @param Eadrax\Core\Data\User $user1
     * @param Eadrax\Core\Data\User $user2
     * @param Eadrax\Core\Usecase\Update\Add\Proposal $proposal
     */
    function it_can_notify_trackers($user1, $user2, $repository, $mail, $proposal)
    {
        $user1->username = 'Foo.bar';
        $user1->email = 'foo@bar.com';
        $user2->username = 'Bar.baz';
        $user2->email = 'bar@baz.com';
        $proposal->name = 'Foobar';

        $repository->get_user_and_project_trackers($this)->shouldBeCalled()
            ->willReturn(array($user1, $user2));

        $message = <<<EOT
Hey Foo.bar,

Author has made a new update on WIPUP called "Foobar". You can check it out with the link below:

TODO

You are receiving this notification because you are tracking their activity.

Cheers,
The WIPUP Team
EOT;
        $mail->send(
            'foo@bar.com',
            'noreply@wipup.org',
            'Author has made a new update on WIPUP: Foobar',
            $message
        )->shouldBeCalled(1);

        $message = <<<EOT
Hey Bar.baz,

Author has made a new update on WIPUP called "Foobar". You can check it out with the link below:

TODO

You are receiving this notification because you are tracking their activity.

Cheers,
The WIPUP Team
EOT;
        $mail->send(
            'bar@baz.com',
            'noreply@wipup.org',
            'Author has made a new update on WIPUP: Foobar',
            $message
        )->shouldBeCalled(1);
        $this->notify_trackers($proposal);
    }
}
