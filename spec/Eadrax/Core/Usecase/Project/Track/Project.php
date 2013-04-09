<?php

namespace spec\Eadrax\Core\Usecase\Project\Track;

use PHPSpec2\ObjectBehavior;

class Project extends ObjectBehavior
{
    /**
     * @param Eadrax\Core\Data\Project $project
     * @param Eadrax\Core\Data\User $author
     * @param Eadrax\Core\Tool\Mail $mail
     */
    function let($project, $author, $mail)
    {
        $author->username = 'Username';
        $project->id = 'id';
        $project->author = $author;
        $project->name = 'Foo';
        $this->beConstructedWith($project, $mail);
        $this->id->shouldBe('id');
        $this->author->shouldBe($author);
        $this->name->shouldBe('Foo');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Eadrax\Core\Usecase\Project\Track\Project');
    }

    function it_is_a_project_data()
    {
        $this->shouldHaveType('Eadrax\Core\Data\Project');
    }

    /**
     * @param Eadrax\Core\Usecase\Project\Track\Fan $fan
     */
    function it_notifies_the_author_about_a_new_fan($fan, $mail)
    {
        $fan->username = 'Fanusername';
        $message = <<<EOT
Hey Username,

$fan->username is now a new fan of your project "Foo" on WIPUP! They'll be notified whenever you make a new update.

This is obviously because they think you're awesome, so don't disappoint them.

Cheers,
The WIPUP Team
EOT;
        $mail->send(
            'foo@bar.com',
            'noreply@wipup.org',
            'Your project "Foo" has a new fan on WIPUP!',
            $message
        )->shouldBeCalled();
        $this->notify_author($fan);
    }
}
