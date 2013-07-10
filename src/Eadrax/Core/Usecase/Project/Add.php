<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Add\Interactor;
use Eadrax\Core\Usecase\Project\Add\Proposal;
use Eadrax\Core\Usecase\Project\Add\Author;
use Eadrax\Core\Usecase;

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
            $this->get_proposal(),
            $this->get_author(),
            $this->get_project_prepare()
        );
    }

    private function get_proposal()
    {
        return new Proposal(
            $this->data['project'],
            $this->repositories['project_add'],
            $this->tools['authenticator']
        );
    }

    private function get_author()
    {
        return new Author(
            $this->tools['authenticator']
        );
    }

    private function get_project_prepare()
    {
        $project_prepare = new Usecase\Project\Prepare(
            $this->data,
            $this->tools
        );
        return $project_prepare->fetch();
    }
}
