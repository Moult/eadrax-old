<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

class Paste extends Data\Paste implements Proposal
{
    public $project;
    public $private;
    public $text;
    public $syntax;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $paste)
    {
        $this->project = $paste->project;
        $this->private = $paste->private;
        $this->text = $paste->text;
        $this->syntax = $paste->syntax;
    }

    public function submit()
    {
        $this->id = $this->repository->save_paste(
            $this->project->id,
            $this->private,
            $this->text,
            $this->syntax
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
