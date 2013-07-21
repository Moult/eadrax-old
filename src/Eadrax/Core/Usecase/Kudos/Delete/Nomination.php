<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Delete;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Nomination extends Data\Update
{
    public $id;
    private $repository;
    private $authenticator;
    private $logged_in_user;

    public function __construct(Data\Update $update, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $update->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->logged_in_user = $authenticator->get_user();
    }

    public function has_kudos()
    {
        return $this->repository->does_update_have_kudos($this->id, $this->logged_in_user->id);
    }

    public function delete_kudos()
    {
        $this->repository->delete_kudos_from_update($this->logged_in_user->id, $this->id);
    }
}
