<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update;
use Eadrax\Core\Usecase\Update\Add\Interactor;
use Eadrax\Core\Usecase\Update\Add\Project;
use Eadrax\Core\Usecase\Update\Add\Text;
use Eadrax\Core\Usecase\Update\Add\Paste;
use Eadrax\Core\Usecase\Update\Add\Image;
use Eadrax\Core\Usecase\Update\Add\Sound;
use Eadrax\Core\Usecase\Update\Add\Video;
use Eadrax\Core\Usecase\Update\Add\Website;
use Eadrax\Core\Usecase;
use Eadrax\Core\Data;

class Add
{
    private $data;
    private $repositories;
    private $tools;

    public function __construct(array $data, array $repositories, array $tools)
    {
        $this->data = $data;
        $this->repositories = $repositories;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_project(),
            $this->get_proposal(),
            $this->get_update_prepare()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['update']->project,
            $this->repositories['update_add'],
            $this->tools['authenticator'],
            $this->tools['emailer'],
            $this->tools['formatter']
        );
    }

    private function get_proposal()
    {
        if ($this->data['update'] instanceof Data\Text)
            return new Text(
                $this->repositories['update_add']
            );
        elseif ($this->data['update'] instanceof Data\Paste)
            return new Paste(
                $this->repositories['update_add']
            );
        elseif ($this->data['update'] instanceof Data\Image)
            return new Image(
                $this->repositories['update_add']
            );
        elseif ($this->data['update'] instanceof Data\Sound)
            return new Sound(
                $this->repositories['update_add']
            );
        elseif ($this->data['update'] instanceof Data\Video)
            return new Video(
                $this->repositories['update_add']
            );
        elseif ($this->data['update'] instanceof Data\Website)
            return new Website(
                $this->repositories['update_add']
            );
    }

    private function get_update_prepare()
    {
        $usecase = new Usecase\Update\Prepare(
            $this->data,
            $this->tools
        );
        return $usecase->fetch();
    }
}
