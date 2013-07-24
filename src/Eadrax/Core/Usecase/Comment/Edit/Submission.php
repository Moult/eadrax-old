<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Submission extends Data\Comment
{
    public $id;
    public $text;
    private $repository;
    private $authenticator;
    private $validator;

    public function __construct(Data\Comment $comment, Repository $repository, Tool\Authenticator $authenticator, Tool\Validator $validator)
    {
        $this->id = $comment->id;
        $this->text = $comment->text;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->validator = $validator;
    }

    public function authorise()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->repository->get_comment_author_id($this->id))
            throw new Exception\Authorisation('Only comment authors can delete comments.');
    }

    public function validate()
    {
        $this->validator->setup(array(
            'text' => $this->text
        ));
        $this->validator->rule('text', 'not_empty');
        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function update()
    {
        $this->repository->update_comment($this->id, $this->text);
    }
}
