<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook;
use Eadrax\Core\Usecase\Hook\Delete\Interactor;
use Eadrax\Core\Usecase\Hook\Delete\Project;
use Eadrax\Core\Usecase\Hook\Delete\Service;

class Delete
{
    private $data;
    private $repositories;
    private $tools;

    /**
     * Deletes a service attached to a service
     *
     * Data required:
     * $project->id
     * $hook->id
     *
     * @param array $data         An array containing Data\Hook and Data\Project
     * @param array $repositories An array containing Hook\Delete\Repository
     * @param array $tools        An array containing Tool\Authenticator
     *
     * @throw Exception\Authorisation If you do not own the project
     *
     * @return void
     */
    public function __construct(Array $data, Array $repositories, Array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->data['hook'],
            $this->get_project()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->repositories['hook_delete'],
            $this->tools['authenticator']
        );
    }
}
