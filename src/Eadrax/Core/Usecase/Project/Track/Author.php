<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Author extends Data\User
{
    public $id;
    public $email;
    public $username;
    private $repository;
    private $authenticator;
    private $emailer;
    private $formatter;
    private $fan;

    public function __construct(Data\Project $project, Repository $repository, Tool\Authenticator $authenticator, Tool\Emailer $emailer, Tool\Formatter $formatter)
    {
        list($this->id, $this->username) = $repository->get_project_author_id_and_username($project->id);
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->emailer = $emailer;
        $this->formatter = $formatter;
        $this->fan = $this->authenticator->get_user();
    }

    public function get_id()
    {
        return $this->id;
    }

    public function remove_fan_from_all_projects()
    {
        $this->repository->delete_fan_from_projects_owned_by_user($this->fan->id, $this->id);
    }

    public function notify_about_new_project_tracker($project_id)
    {
        list($project_name, $this->email) = $this->repository->get_project_name_and_author_email($project_id);
        $this->formatter->setup(array(
            'author_username' => $this->username,
            'fan_username' => $this->fan->username,
            'fan_id' => $this->fan->id,
            'project_name' => $project_name,
            'project_id' => $project_id
        ));
        $this->emailer->set_body($this->formatter->format('email_project_track_body'));
        $this->formatter->setup(array(
            'fan_username' => $this->fan->username,
            'project_name' => $project_name
        ));
        $this->emailer->set_subject($this->formatter->format('email_project_track_subject'));
        $this->emailer->set_to($this->email);
        $this->emailer->send();
    }

    public function get_number_of_projects()
    {
        return $this->repository->get_number_of_projects_owned_by_author($this->id);
    }

    public function has_fan()
    {
        return $this->repository->does_author_have_fan($this->id, $this->fan->id);
    }
}
