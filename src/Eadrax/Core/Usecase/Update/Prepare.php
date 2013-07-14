<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update;
use Eadrax\Core\Usecase\Update\Prepare\Interactor;
use Eadrax\Core\Usecase\Update\Prepare\Text;
use Eadrax\Core\Usecase\Update\Prepare\Paste;
use Eadrax\Core\Usecase\Update\Prepare\Image;
use Eadrax\Core\Usecase\Update\Prepare\Sound;
use Eadrax\Core\Usecase\Update\Prepare\Video;
use Eadrax\Core\Usecase\Update\Prepare\Website;
use Eadrax\Core\Data;


class Prepare
{
    private $data;
    private $tools;

    public function __construct(array $data, array $tools)
    {
        $this->data = $data;
        $this->tools = $tools;
    }

    public function fetch()
    {
        return new Interactor(
            $this->get_proposal()
        );
    }

    private function get_proposal()
    {
        if ($this->data['update'] instanceof Data\Text)
            return new Text(
                $this->data['update'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Paste)
            return new Paste(
                $this->data['update'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Image)
            return new Image(
                $this->data['update'],
                $this->tools['photoshopper'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Sound)
            return new Sound(
                $this->data['update'],
                $this->tools['filemanager'],
                $this->tools['soundeditor'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Video)
            return new Video(
                $this->data['update'],
                $this->tools['filemanager'],
                $this->tools['videoeditor'],
                $this->tools['validator']
            );
        elseif ($this->data['update'] instanceof Data\Website)
            return new Website(
                $this->data['update'],
                $this->tools['browser'],
                $this->tools['photoshopper'],
                $this->tools['validator']
            );
    }
}
