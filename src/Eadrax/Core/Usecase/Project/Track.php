<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Track\Interactor;
use Eadrax\Core\Usecase\Project\Track\Author;
use Eadrax\Core\Usecase\Project\Track\Fan;
use Eadrax\Core\Usecase\Project\Track\Project;
use Eadrax\Core\Usecase\Project\Track\User;
use Eadrax\Core\Data;

class Track
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
            $this->get_fan(),
            $this->get_project(),
            $this->get_user_track()
        );
    }

    private function get_author()
    {
        return new Author(
            $this->data['project'],
            $this->repositories['project_track'],
            $this->tools['authenticator'],
            $this->tools['emailer'],
            $this->tools['formatter']
        );
    }

    private function get_fan()
    {
        return new Fan(
            $this->repositories['project_track'],
            $this->tools['authenticator']
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->repositories['project_track'],
            $this->tools['authenticator']
        );
    }

    public function get_user_track()
    {
        return new User\Track(
            array(
                'user' => new Data\User
            ),
            array(
                'user_track' => $this->repositories['user_track']
            ),
            array(
                'authenticator' => $this->tools['authenticator'],
                'emailer' => $this->tools['emailer'],
                'formatter' => $this->tools['formatter']
            )
        );
    }
}
