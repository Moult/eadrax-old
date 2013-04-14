<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Project extends Data\Project
{
    private $mail;

    public function __construct(Data\Project $project, Tool\Mail $mail)
    {
        $this->id = $project->id;
        $this->author = $project->author;
        $this->name = $project->name;

        $this->mail = $mail;
    }

    public function notify_author(Fan $fan)
    {
        $author = $this->author;
        $message = <<<EOT
Hey $author->username,

$fan->username is now a new fan of your project "$this->name" on WIPUP! They'll be notified whenever you make a new update.

This is obviously because they think you're awesome, so don't disappoint them.

Cheers,
The WIPUP Team
EOT;
        $this->mail->send(
            'foo@bar.com',
            'Your project "Foo" has a new fan on WIPUP!',
            $message
        );
    }
}
