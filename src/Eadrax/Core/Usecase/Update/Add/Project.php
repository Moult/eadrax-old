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
    public $id;
    public $name;
    public $author;
    private $repository;
    private $authenticator;
    private $emailer;
    private $formatter;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator, Tool\Emailer $emailer, Tool\Formatter $formatter)
    {
        list($project_name, $author_id, $author_username) = $repository->get_project_name_and_author_id_and_username($project->id);

        $this->id = $project->id;
        $this->name = $project_name;
        $this->author = new Data\User;
        $this->author->id = $author_id;
        $this->author->username = $author_username;

        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->emailer = $emailer;
        $this->formatter = $formatter;
    }

    public function authorise_ownership()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->author->id)
            throw new Exception\Authorisation('You are not allowed to add an update to this project');
    }

    public function notify_trackers()
    {
        $trackers = $this->repository->get_id_and_username_and_email_of_all_trackers($this->id);
        foreach ($trackers as $tracker)
        {
            list($tracker_id, $tracker_username, $tracker_email) = $tracker;
            $this->formatter->setup(array(
                $tracker_id,
                $tracker_username,
                $this->author->id,
                $this->author->username,
                $this->id,
                $this->name
            ));
            $this->emailer->set_tO($tracker_email);
            $this->emailer->set_subject($this->formatter->format('email_update_add_subject'));
            $this->emailer->set_body($this->formatter->format('email_update_add_body'));
            $this->emailer->queue();
        }

        $this->emailer->send_queue();
    }
}
