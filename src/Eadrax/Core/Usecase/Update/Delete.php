<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update;
use Eadrax\Core\Usecase\Update\Delete\Interactor;
use Eadrax\Core\Usecase\Update\Delete\Project;
use Eadrax\Core\Usecase\Update\Delete\Proposal;

class Delete
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct(Array $data, Array $repositories, Array $tools)
    {
        $data['project'] = $repositories['update_delete']->get_parent_project($data['update']);
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_project(),
            $this->get_proposal()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->tools['auth']
        );
    }

    private function get_proposal()
    {
        return new Proposal(
            $this->data['update'],
            $this->repositories['update_delete'],
            $this->tools['filesystem']
        );
    }
}
