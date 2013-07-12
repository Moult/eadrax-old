<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Untrack;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Idol extends Data\User
{
    public $id;
    private $repository;
    private $authenticator;
    private $fan;

    public function __construct(Data\User $user, Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->id = $user->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
        $this->fan = $this->authenticator->get_user();
    }

    public function has_fan()
    {
        return $this->repository->does_idol_have_fan($this->id, $this->fan->id);
    }

    public function add_fan()
    {
        $this->repository->add_fan_to_idol($this->fan->id, $this->id);
    }
}
