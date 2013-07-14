<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Image extends Data\Image implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $width;
    public $height;
    private $repository;
    private $authenticator;

    public function __construct(Data\Image $image, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $image->id;
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

    public function load_prepared_proposal(Data\Update $image)
    {
        $this->private = $image->private;
        $this->file = $image->file;
        $this->thumbnail = $image->thumbnail;
        $this->width = $image->width;
        $this->height = $image->height;
    }

    public function submit()
    {
        $this->repository->purge_files($this->id);

        $file_path = $this->repository->save_file(
            $this->file->name,
            $this->file->tmp_name,
            $this->file->mimetype,
            $this->file->filesize_in_bytes,
            $this->file->error_code
        );

        $this->repository->save_image(
            $this->id,
            $this->private,
            $file_path,
            $this->repository->save_generated_file($this->thumbnail),
            $this->width,
            $this->height
        );
    }
}
