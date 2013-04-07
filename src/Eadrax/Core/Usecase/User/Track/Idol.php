<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Idol extends Data\User
{
    private $mail;

    public function __construct(Data\User $user, Repository $repository, Tool\Mail $mail)
    {
        $this->id = $user->id;
        $user_details = $repository->get_username_and_email($this->id);
        $this->username = $user_details->username;
        $this->email = $user_details->email;

        $this->mail = $mail;
    }

    public function notify_new_fan($fan)
    {
        $message = <<<EOT
Hey $this->username,

$fan->username is now a new fan of your work on WIPUP! They'll be notified whenever you make a new update to any of your projects.

Keep up the great work, stay ambitious, and create like no tomorrow!

Cheers,
The WIPUP Team
EOT;
        $this->mail->send(
            $this->email,
            'noreply@wipup.org',
            'You have a new fan on WIPUP!',
            $message
        );
    }
}
