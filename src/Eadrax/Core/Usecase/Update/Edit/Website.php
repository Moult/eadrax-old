<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Website extends Data\Website implements Proposal
{
    public $project;
    public $private;
    public $thumbnail;
    public $url;
    private $repository;
    private $authenticator;

    public function __construct(Data\Website $website, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $website->id;
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

    public function load_prepared_proposal(Data\Update $website)
    {
        $this->private = $website->private;
        $this->url = $this->normalise_url($website->url);
        $this->thumbnail = $website->thumbnail;
    }

    private function normalise_url($url)
    {
        return ((substr($url, 0, 7) !== 'http://') ? 'http://' : '').$url;
    }

    public function submit()
    {
        $this->repository->purge_files($this->id);

        $this->repository->save_website(
            $this->id,
            $this->private,
            $this->url,
            $this->repository->save_generated_file($this->thumbnail)
        );
    }
}
