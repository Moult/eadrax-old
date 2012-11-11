<?php

namespace Eadrax\Eadrax\Data;

class File extends Core
{
    /**
     * Name of the file
     * @var string
     */
    public $name;

    /**
     * File extension
     * @var string
     */
    public $extension;

    /**
     * Filetype mimetype
     * @var string
     */
    public $mimetype;

    /**
     * Filesize in bytes
     * @var int
     */
    public $filesize;

    /** @ignore */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /** @ignore */
    public function get_name()
    {
        return $this->name;
    }

    /** @ignore */
    public function set_extension($extension)
    {
        $this->extension = $extension;
    }

    /** @ignore */
    public function get_extension()
    {
        return $this->extension;
    }

    /** @ignore */
    public function set_mimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    /** @ignore */
    public function get_mimetype()
    {
        return $this->mimetype;
    }

    /** @ignore */
    public function set_filesize($filesize)
    {
        $this->filesize = (int) $filesize;
    }

    /** @ignore */
    public function get_filesize()
    {
        return $this->filesize;
    }
}
