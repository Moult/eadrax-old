<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;

use Eadrax\Core\Data;
use Eadrax\Core\Exception;

class Proposal extends Data\Project
{
    private $repository;

    public function __construct(Data\Project $project, Repository $repository)
    {
        foreach ($project as $property => $value)
        {
            $this->$property = $value;
        }

        $this->repository = $repository;
    }

    public function verify_ownership(User $user)
    {
        $owner = $this->repository->get_owner($this);
        if ($user->id !== $owner->id)
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }

    public function update()
    {
        $this->repository->update($this);
    }
}
