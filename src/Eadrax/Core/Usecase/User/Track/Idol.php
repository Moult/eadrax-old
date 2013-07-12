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
    public $id;
    private $repository;
    private $emailer;
    private $formatter;

    public function __construct(Data\User $user, Repository $repository, Tool\Emailer $emailer, Tool\Formatter $formatter)
    {
        $this->id = $user->id;
        $this->repository = $repository;
        $this->emailer = $emailer;
        $this->formatter = $formatter;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function notify_new_fan($fan_id)
    {
        list($idol_username, $idol_email) = $this->repository->get_username_and_email($this->id);
        $fan_username = $this->repository->get_username($fan_id);

        $this->formatter->setup(array(
            'idol_username' => $idol_username,
            'fan_id' => $fan_id,
            'fan_username' => $fan_username
        ));

        $this->emailer->set_to($idol_email);
        $this->emailer->set_subject($this->formatter->format('email_user_track_subject'));
        $this->emailer->set_body($this->formatter->format('email_user_track_body'));
        $this->emailer->send();
    }
}
