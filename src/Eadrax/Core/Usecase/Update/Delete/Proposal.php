<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Usecase\Update\Delete\Repository;
use Eadrax\Core\Tool;

class Proposal extends Data\Update
{
    private $filesystem;
    private $repository;

    public function __construct(Data\Update $update, Repository $repository, Tool\Filesystem $filesystem)
    {
        $this->id = $update->id;
        $update_details = $repository->get_update_type_and_content($this->id);
        $this->type = $update_details->type;
        $this->content = $update_details->content;

        $this->filesystem = $filesystem;
        $this->repository = $repository;
    }

    public function delete_thumbnail()
    {
        if ($this->type === 'website'
            OR $this->type === 'file/image'
            OR $this->type === 'file/video'
            OR $this->type === 'file/sound')
            return $this->filesystem->delete_thumbnail($this->content);
    }

    public function delete_upload()
    {
        if (substr($this->type, 0, 4) === 'file')
            return $this->filesystem->delete_upload($this->content);
    }

    public function delete()
    {
        $this->repository->delete_update($this);
    }
}
