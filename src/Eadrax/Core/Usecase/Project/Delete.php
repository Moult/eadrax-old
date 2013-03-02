<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;

use Eadrax\Core\Usecase\Project\Delete\Interactor;
use Eadrax\Core\Usecase\Project\Delete\User;
use Eadrax\Core\Usecase\Project\Delete\Proposal;
use Eadrax\Core\Data;

class Delete
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct($data, $repositories, $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_user(),
            $this->get_proposal()
        );
    }

    private function get_user()
    {
        return new User(
            $this->tools['auth']->get_user(),
            $this->tools['auth']
        );
    }

    private function get_proposal()
    {
        return new Proposal(
            $this->get_project(),
            $this->repositories['project_delete']
        );
    }

    private function get_project()
    {
        $project = new Data\Project;
        $project->id = $this->data['id'];
        return $project;
    }
}
