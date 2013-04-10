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
            $this->repositories['hook_delete'],
            $this->tools['auth']
        );
    }

    private function get_service()
    {
        return new Service(
            $this->data['hook']
        );
    }
}
