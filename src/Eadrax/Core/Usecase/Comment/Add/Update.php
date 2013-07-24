<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Add;
use Eadrax\Core\Data;

class Update extends Data\Update
{
    public $id;
    private $repository;

    public function __construct(Data\Update $update, Repository $repository)
    {
        $this->id = $update->id;
        $this->repository = $repository;
    }

    public function does_exist()
    {
        return $this->repository->does_update_exist($this->id);
    }
}
