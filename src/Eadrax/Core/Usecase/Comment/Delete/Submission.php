<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Submission extends Data\Comment
{
    public $id;
    private $repository;
    private $authenticator;

    public function __construct(Data\Comment $comment, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $comment->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->repository->get_comment_author_id($this->id))
            throw new Exception\Authorisation('Only comment authors can delete comments.');
    }

    public function delete()
    {
        $this->repository->delete_comment($this->id);
    }
}
