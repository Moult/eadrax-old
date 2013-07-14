<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Sound extends Data\Sound implements Proposal
{
    public $project;
    public $private;
    public $file;
    public $thumbnail;
    public $length;
    public $filesize;
    private $repository;
    private $authenticator;

    public function __construct(Data\Sound $sound, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $sound->id;
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

    public function load_prepared_proposal(Data\Update $sound)
    {
        $this->private = $sound->private;
        $this->file = $sound->file;
        $this->thumbnail = $sound->thumbnail;
        $this->length = $sound->length;
        $this->filesize = $sound->filesize;
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

        $this->repository->save_sound(
            $this->id,
            $this->private,
            $file_path,
            $this->repository->save_generated_file($this->thumbnail),
            $this->length,
            $this->filesize
        );
    }
}
