<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

class Sound extends Data\Sound implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $length;
    public $filesize;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function load_prepared_proposal(Data\Update $sound)
    {
        $this->project = $sound->project;
        $this->private = $sound->private;
        $this->file = $sound->file;
        $this->thumbnail = $sound->thumbnail;
        $this->length = $sound->length;
        $this->filesize = $sound->filesize;
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

        $this->id = $this->repository->save_sound(
            $this->project->id,
            $this->private,
            $file_path,
            $this->repository->save_generated_file($this->thumbnail),
            $this->length,
            $this->filesize
        );
    }

    public function get_id()
    {
        return $this->id;
    }
}
