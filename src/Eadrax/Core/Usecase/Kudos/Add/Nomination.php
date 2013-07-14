<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Nomination extends Data\Update
{
    public $id;
    private $repository;
    private $authenticator;
    private $emailer;
    private $formatter;
    private $logged_in_user;

    public function __construct(Data\Update $update, Repository $repository, Tool\Authenticator $authenticator, Tool\Emailer $emailer, Tool\Formatter $formatter)
    {
        $this->id = $update->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->emailer = $emailer;
        $this->formatter = $formatter;
        $this->logged_in_user = $authenticator->get_user();
    }

    public function has_kudos()
    {
        return $this->repository->does_update_have_kudos($this->id, $this->logged_in_user->id);
    }

    public function add_kudos()
    {
        $this->repository->add_kudos_to_update($this->logged_in_user->id, $this->id);
    }

    public function notify_author()
    {
        list($project_id, $project_name, $author_id, $author_username, $author_email) = $this->repository->get_project_id_and_name_and_author_id_and_name_and_email($this->id);
        $this->formatter->setup(array(
            'project_id' => $project_id,
            'project_name' => $project_name,
            'author_id' => $author_id,
            'author_username' => $author_username,
            'fan_id' => $this->logged_in_user->id,
            'fan_username' => $this->logged_in_user->username
        ));
        $this->emailer->set_to($author_email);
        $this->emailer->set_subject($this->formatter->format('email_kudos_add_subject'));
        $this->emailer->set_body($this->formatter->format('email_kudos_add_body'));
        $this->emailer->send();
    }
}
