<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Text extends Data\Text implements Proposal
{
    public $project;
    public $private;
    public $message;
    private $repository;
    private $authenticator;

    public function __construct(Data\Text $text, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $text->id;
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

    public function load_prepared_proposal(Data\Update $text)
    {
        $this->private = $text->private;
        $this->message = $text->message;
    }

    public function submit()
    {
        $this->repository->save_text(
            $this->id,
            $this->private,
            $this->message
        );
    }
}
