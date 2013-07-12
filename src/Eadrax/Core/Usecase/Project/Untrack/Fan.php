<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    private $repository;
    private $authenticator;

    public function __construct(Repository $repository, Tool\Authenticator $authenticator)
    {
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in to untrack a project');
    }

    public function has_idol($idol_id)
    {
        $fan = $this->authenticator->get_user();
        return $this->repository->does_fan_have_idol($fan->id, $idol_id);
    }
}
