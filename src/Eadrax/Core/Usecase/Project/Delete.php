<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Delete\Interactor;
use Eadrax\Core\Usecase\Project\Delete\Author;
use Eadrax\Core\Usecase\Project\Delete\Proposal;

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
            $this->get_author(),
            $this->get_proposal()
        );
    }

    private function get_author()
    {
        return new Author(
            $this->tools['authenticator']
        );
    }

    private function get_proposal()
    {
        return new Proposal(
            $this->data['project'],
            $this->repositories['project_delete']
        );
    }
}
