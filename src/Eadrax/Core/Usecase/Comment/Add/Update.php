<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Update extends Data\Update
{
    public $id;
    private $repository;
    private $emailer;
    private $formatter;

    public function __construct(Data\Update $update, Repository $repository, Tool\Emailer $emailer, Tool\Formatter $formatter)
    {
        $this->id = $update->id;
        $this->repository = $repository;
        $this->emailer = $emailer;
        $this->formatter = $formatter;
    }

    public function does_exist()
    {
        return $this->repository->does_update_exist($this->id);
    }

    public function notify_author_about_comment($comment_author_username, $comment_text)
    {
        $update_details = $this->repository->get_update_details($this->id);

        $this->formatter->format(array(
            'author_username' => $update_details['author_username'],
            'update_id' => $this->id,
            'project_id' => $update_details['project_id'],
            'project_name' => $update_details['project_name'],
            'comment_author_username' => $comment_author_username,
            'comment_text' => $comment_text
        ));
        $this->emailer->set_to($update_details['author_email']);
        $this->emailer->set_subject($this->formatter->format('Email_Comment_Add_Subject'));
        $this->emailer->set_body($this->formatter->format('Email_Comment_Add_Body'));
        $this->emailer->send();
    }
}
