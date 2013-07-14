<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Paste extends Data\Paste implements Proposal
{
    public $project;
    public $private;
    public $text;
    public $syntax;
    private $repository;
    private $authenticator;

    public function __construct(Data\Paste $paste, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $paste->id;
        $this->project = new Data\Project;
        $this->project->author = new Data\User;
        $this->project->author->id = $repository->get_author_id($this->id);
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise_ownership()
    {
        $logged_in_user = $this->authenticator->get_user();
        if ($logged_in_user->id !== $this->project->author->id)
            throw new Exception\Authorisation('You are not allowed to edit this update.');
    }

    public function load_prepared_proposal(Data\Update $paste)
    {
        $this->private = $paste->private;
        $this->text = $paste->text;
        $this->syntax = $paste->syntax;
    }

    public function submit()
    {
        $this->repository->save_paste(
            $this->id,
            $this->private,
            $this->text,
            $this->syntax
        );
    }
}
