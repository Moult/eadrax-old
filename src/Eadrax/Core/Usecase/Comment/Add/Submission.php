<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Submission extends Data\Comment
{
    public $text;
    public $author;
    private $repository;
    private $authenticator;
    private $validator;

    public function __construct(Data\Comment $comment, Repository $repository, Tool\Authenticator $authenticator, Tool\Validator $validator)
    {
        $this->text = $comment->text;
        $this->update = $comment->update;
        $this->author = $authenticator->get_user();
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->validator = $validator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('Only logged in users can add comments');
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

    public function submit()
    {
        $this->repository->add_comment($this->text, $this->author->id, $this->update->id);
    }

    public function get_author_username()
    {
        return $this->author->username;
    }

    public function get_text()
    {
        return $this->text;
    }
}
