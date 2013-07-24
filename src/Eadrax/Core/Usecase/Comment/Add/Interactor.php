<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Add;

class Interactor
{
    private $submission;
    private $update;

    public function __construct(Submission $submission, Update $update)
    {
        $this->submission = $submission;
        $this->update = $update;
    }

    public function interact()
    {
        if ( ! $this->update->does_exist())
            return;

        $this->submission->authorise();
        $this->submission->validate();
        $this->submission->submit();

        $this->update->notify_author_about_comment(
            $this->submission->get_author_username(),
            $this->submission->get_text()
        );
    }
}
