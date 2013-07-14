<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

class Image extends Data\Image implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $width;
    public $height;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $image)
    {
        $this->project = $image->project;
        $this->private = $image->private;
        $this->file = $image->file;
        $this->thumbnail = $image->thumbnail;
        $this->width = $image->width;
        $this->height = $image->height;
    }

    public function submit()
    {
        $file_path = $this->repository->save_file(
            $this->file->name,
            $this->file->tmp_name,
            $this->file->mimetype,
            $this->file->filesize_in_bytes,
            $this->file->error_code
        );

        $this->id = $this->repository->save_image(
            $this->project->id,
            $this->private,
            $file_path,
            $this->repository->save_generated_file($this->thumbnail),
            $this->width,
            $this->height
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
