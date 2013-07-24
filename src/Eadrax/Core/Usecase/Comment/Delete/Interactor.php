<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Delete;

class Interactor
{
    private $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function interact()
    {
        $this->submission->authorise();
        $this->submission->delete();
    }
}
