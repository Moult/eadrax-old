<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook;
use Eadrax\Core\Usecase\Hook\Add\Interactor;
use Eadrax\Core\Usecase\Hook\Add\Project;
use Eadrax\Core\Usecase\Hook\Add\Service;

class Add
{
    private $data;
    private $repositories;
    private $tools;

    /**
     * Adds a hook to a project.
     *
     * Data required:
     * $project->id
     * $hook->url
     *
     * @param array $data         An array containing Data\Project and Data\Hook
     * @param array $repositories An array containing Hook\Add\Repository
     * @param array $tools        An array containing Tool\Authenticator and
     *                            Tool\Validator
     *
     * @throw Exception\Authorisation If you do not own the project
     * @throw Exception\Validation    If the hook url is not valid
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
            $this->get_project(),
            $this->get_service()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->repositories['hook_add'],
            $this->tools['authenticator']
        );
    }

    private function get_service()
    {
        return new Service(
            $this->data['hook'],
            $this->tools['validator']
        );
    }
}
