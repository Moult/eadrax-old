<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update;
use Eadrax\Core\Usecase\Update\Add\Interactor;
use Eadrax\Core\Usecase\Update\Add\Project;
use Eadrax\Core\Usecase\Update\Add\Proposal;

class Add
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
            $this->get_proposal()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->repositories['update_add'],
            $this->tools['auth'],
            $this->tools['mail']
        );
    }

    private function get_proposal()
    {
        return new Proposal(
            $this->data['update'],
            $this->repositories['update_add'],
            $this->tools['filesystem'],
            $this->tools['image'],
            $this->tools['upload'],
            $this->tools['validation']
        );
    }
}
