<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment;
use Eadrax\Core\Usecase\Comment\Edit\Interactor;
use Eadrax\Core\Usecase\Comment\Edit\Submission;

class Edit
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
            $this->repositories['comment_edit'],
            $this->tools['authenticator'],
            $this->tools['validator']
        );
    }
}
