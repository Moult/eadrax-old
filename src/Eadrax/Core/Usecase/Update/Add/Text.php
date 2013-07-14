<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

class Text extends Data\Text implements Proposal
{
    public $project;
    public $private;
    public $message;
    private $repository;

    public function __construct(Data\Text $text, Repository $repository)
    {
        $this->project = $text->project;
        $this->private = $text->private;
        $this->message = $text->message;
        $this->repository = $repository;
    }

    public function submit()
    {
        $this->id = $this->repository->save_text(
            $this->project->id,
            $this->private,
            $this->message
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
