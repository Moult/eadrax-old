<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update;
use Eadrax\Core\Usecase\Update\Edit\Interactor;
use Eadrax\Core\Usecase\Update\Edit\Text;
use Eadrax\Core\Usecase\Update\Edit\Paste;
use Eadrax\Core\Usecase\Update\Edit\Image;
use Eadrax\Core\Usecase\Update\Edit\Sound;
use Eadrax\Core\Usecase\Update\Edit\Video;
use Eadrax\Core\Usecase\Update\Edit\Website;
use Eadrax\Core\Usecase;
use Eadrax\Core\Data;

class Edit
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
            $this->get_proposal(),
            $this->get_update_prepare()
        );
    }

    private function get_proposal()
    {
        if ($this->data['update'] instanceof Data\Text)
            return new Text(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
            );
        elseif ($this->data['update'] instanceof Data\Paste)
            return new Paste(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
            );
        elseif ($this->data['update'] instanceof Data\Image)
            return new Image(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
            );
        elseif ($this->data['update'] instanceof Data\Sound)
            return new Sound(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
            );
        elseif ($this->data['update'] instanceof Data\Video)
            return new Video(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
            );
        elseif ($this->data['update'] instanceof Data\Website)
            return new Website(
                $this->data['update'],
                $this->repositories['update_add'],
                $this->tools['authenticator']
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
