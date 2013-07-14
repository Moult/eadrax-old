<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

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

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $video)
    {
        $this->project = $video->project;
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
        $this->id = $this->repository->save_video(
            $this->project->id,
            $this->private,
            $this->repository->save_generated_file($this->file),
            $this->repository->save_generated_file($this->thumbnail),
            $this->length,
            $this->filesize,
            $this->width,
            $this->height
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
