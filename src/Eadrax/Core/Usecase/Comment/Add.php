<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment;
use Eadrax\Core\Usecase\Comment\Add\Interactor;
use Eadrax\Core\Usecase\Comment\Add\Submission;
use Eadrax\Core\Usecase\Comment\Add\Update;

class Add
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
            $this->get_submission(),
            $this->get_update()
        );
    }

    private function get_submission()
    {
        return new Submission(
            $this->data['comment'],
            $this->repositories['comment_add'],
            $this->tools['authenticator'],
            $this->tools['validator']
        );
    }

    private function get_update()
    {
        return new Update(
            $this->data['comment']->update,
            $this->repositories['comment_add']
        );
    }
}
