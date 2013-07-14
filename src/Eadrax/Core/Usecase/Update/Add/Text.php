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

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $text)
    {
        $this->project = $text->project;
        $this->private = $text->private;
        $this->message = $text->message;
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
