<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Text extends Data\Text implements Proposal
{
    public $project;
    public $private;
    public $message;
    private $repository;
    private $validator;

    public function __construct(Data\Text $text, Repository $repository, Tool\Validator $validator)
    {
        $this->project = $text->project;
        $this->private = $text->private;
        $this->message = $text->message;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function validate()
    {
        $this->validator->setup(array(
            'message' => $this->message
        ));
        $this->validator->rule('message', 'not_empty');

        if ( ! $this->validator->check())
            throw new Exception\Validation($this->validator->errors());
    }

    public function submit()
    {
        $this->repository->save_text(
            $this->project->id,
            $this->private,
            $this->message
        );
    }
}
