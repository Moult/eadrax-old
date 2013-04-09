<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;
use Eadrax\Core\Usecase\Project\Track\Interactor;
use Eadrax\Core\Usecase\Project\Track\Fan;
use Eadrax\Core\Usecase\Project\Track\Project;
use Eadrax\Core\Usecase\User;

class Track
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct(Array $data, Array $repositories, Array $tools)
    {
        $author = $repositories['project_track']->get_project_author($data['project']);
        $data['project']->author = $author;
        $data['idol'] = $author;

        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_fan(),
            $this->get_project(),
            $this->get_user_track()
        );
    }

    private function get_fan()
    {
        return new Fan(
            $this->repositories['project_track'],
            $this->tools['auth']
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
            $this->tools['mail']
        );
    }

    private function get_user_track()
    {
        return new User\Track\Interactor(
            $this->get_user_track_idol(),
            $this->get_user_track_fan()
        );
    }

    private function get_user_track_idol()
    {
        return new User\Track\Idol(
            $this->data['idol'],
            $this->repositories['user_track'],
            $this->tools['mail']
        );
    }

    private function get_user_track_fan()
    {
        return new User\Track\Fan(
            $this->repositories['user_track'],
            $this->tools['auth']
        );
    }
}
