<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Video extends Data\Video implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $length;
    public $filesize;
    public $width;
    public $height;
    private $repository;
    private $authenticator;

    public function __construct(Data\Video $video, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $video->id;
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

    public function load_prepared_proposal(Data\Update $video)
    {
        $this->private = $video->private;
        $this->file = $video->file;
        $this->thumbnail = $video->thumbnail;
        $this->length = $video->length;
        $this->filesize = $video->filesize;
        $this->width = $video->width;
        $this->height = $video->height;
    }

    public function submit()
    {
        $this->repository->purge_files($this->id);

        $this->repository->save_video(
            $this->id,
            $this->private,
            $this->repository->save_generated_file($this->file),
            $this->repository->save_generated_file($this->thumbnail),
            $this->length,
            $this->filesize,
            $this->width,
            $this->height
        );
    }
}
