<?php

namespace Eadrax\Core\Data;

class File
{
    /**
     * Name of the file
     * @var string
     */
    public $name;

    /**
     * File tmp_name
     * @var string
     */
    public $tmp_name;

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

    /**
     * Error code
     * @var int
     */
    public $error;

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
    public function set_tmp_name($tmp_name)
    {
        $this->tmp_name = $tmp_name;
    }

    /** @ignore */
    public function get_tmp_name()
    {
        return $this->tmp_name;
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

    /** @ignore */
    public function set_error($error)
    {
        $this->error = (int) $error;
    }

    /** @ignore */
    public function get_error()
    {
        return $this->error;
    }
}
