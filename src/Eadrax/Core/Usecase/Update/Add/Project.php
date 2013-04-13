<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Project extends Data\Project
{
    private $repository;
    private $auth;
    private $mail;

    public function __construct(Data\Project $project, Repository $repository, Tool\Auth $auth, Tool\Mail $mail)
    {
        $this->id = $project->id;
        $this->author = $auth->get_user();
        $this->repository = $repository;
        $this->auth = $auth;
        $this->mail = $mail;
    }

    public function authorise()
    {
        $project_author = $this->repository->get_project_author($this);
        if ($this->author->id !== $project_author->id)
            throw new Exception\Authorisation('You are not allowed to add an update to this project');
    }

    public function notify_trackers(Proposal $proposal)
    {
        $trackers = $this->repository->get_user_and_project_trackers($this);
        foreach ($trackers as $tracker)
        {
            $author_username = $this->author->username;
            $message = <<<EOT
Hey $tracker->username,

$author_username has made a new update on WIPUP called "$proposal->name". You can check it out with the link below:

TODO

You are receiving this notification because you are tracking their activity.

Cheers,
The WIPUP Team
EOT;
            $this->mail->send(
                $tracker->email,
                'noreply@wipup.org',
                $author_username.' has made a new update on WIPUP: '.$proposal->name,
                $message
            );
        }
    }
}
