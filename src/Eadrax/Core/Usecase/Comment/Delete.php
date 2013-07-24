<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment;
use Eadrax\Core\Usecase\Comment\Delete\Interactor;
use Eadrax\Core\Usecase\Comment\Delete\Submission;

class Delete
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct(array $data, array $repositories, array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_submission()
        );
    }

    private function get_submission()
    {
        return new Submission(
            $this->data['comment'],
            $this->repositories['comment_delete'],
            $this->tools['authenticator']
        );
    }
}
