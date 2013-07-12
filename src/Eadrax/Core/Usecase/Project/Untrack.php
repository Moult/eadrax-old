<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Untrack\Interactor;
use Eadrax\Core\Usecase\Project\Untrack\Fan;
use Eadrax\Core\Usecase\Project\Untrack\Project;
use Eadrax\Core\Usecase\Project\Untrack\User;
use Eadrax\Core\Data;

class Untrack
{
    public function __construct(array $data, array $repositories, array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_fan(),
            $this->get_project(),
            $this->get_user_untrack()
        );
    }

    private function get_fan()
    {
        return new Fan(
            $this->repositories['project_untrack'],
            $this->tools['authenticator']
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->repositories['project_untrack'],
            $this->tools['authenticator']
        );
    }

    public function get_user_untrack()
    {
        return new User\Untrack(
            array(
                'user' => new Data\User
            ),
            array(
                'user_untrack' => $this->repositories['user_untrack']
            ),
            array(
                'authenticator' => $this->tools['authenticator']
            )
        );
    }
}
