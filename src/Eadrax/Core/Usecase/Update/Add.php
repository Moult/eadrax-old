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
            $this->get_proposal()
        );
    }

    private function get_project()
    {
        return new Project(
            $this->data['project'],
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
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Paste)
            return new Paste(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Image)
            return new Image(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['photoshopper'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Sound)
            return new Sound(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['filemanager'],
                $this->tools['soundeditor'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Video)
            return new Video(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['filemanager'],
                $this->tools['videoeditor'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Website)
            return new Website(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['browser'],
                $this->tools['photoshopper'],
                $this->tools['validator']
            );
    }
}
