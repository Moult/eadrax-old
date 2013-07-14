<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

class Website extends Data\Website implements Proposal
{
    public $project;
    public $private;
    public $thumbnail;
    public $url;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $website)
    {
        $this->project = $website->project;
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
        $this->id = $this->repository->save_website(
            $this->project->id,
            $this->private,
            $this->url,
            $this->repository->save_generated_file($this->thumbnail)
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
