<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

class Fan extends Data\User
{
    public $id;
    private $repository;
    private $authenticator;

    public function __construct(Repository $repository, Tool\Authenticator $authenticator)
    {
        $fan = $authenticator->get_user();
        $this->id = $fan->id;
        $this->repository = $repository;
        $this->authenticator = $authenticator;
    }

    public function authorise()
    {
        if ( ! $this->authenticator->logged_in())
            throw new Exception\Authorisation('You need to be logged in');
    }

    public function get_number_of_projects_owned_by_author($author_id)
    {
        return $this->repository->get_number_of_tracked_projects_owned_by_author($this->id, $author_id);
    }
}
