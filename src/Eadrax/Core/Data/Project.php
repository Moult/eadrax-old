<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Data;

class Project
{
    /** @ignore */
    public $name;
    /** @ignore */
    public $summary;
    /** @ignore */
    public $author;
    /** @ignore */
    public $description;
    /** @ignore */
    public $icon;
    /** @ignore */
    public $website;
    /** @ignore */
    public $views;
    /** @ignore */
    public $last_updated;

    /** @ignore */
    public function get_name()
    {
        return $this->name;
    }

    /** @ignore */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /** @ignore */
    public function get_summary()
    {
        return $this->summary;
    }

    /** @ignore */
    public function set_summary($summary)
    {
        $this->summary = $summary;
    }

    /** @ignore */
    public function get_author()
    {
        if ($this->author instanceof User)
            return $this->author;
        else
            return new User;
    }

    /** @ignore */
    public function set_author(User $author)
    {
        $this->author = $author;
    }

    /** @ignore */
    public function get_description()
    {
        return $this->description;
    }

    /** @ignore */
    public function set_description($description)
    {
        $this->description = $description;
    }

    /** @ignore */
    public function get_icon()
    {
        if ($this->icon instanceof File)
            return $this->icon;
        else
            return new File;
    }

    /** @ignore */
    public function set_icon(File $icon)
    {
        $this->icon = $icon;
    }

    /** @ignore */
    public function get_website()
    {
        return $this->website;
    }

    /** @ignore */
    public function set_website($website)
    {
        $this->website = $website;
    }

    /** @ignore */
    public function get_views()
    {
        return $this->views;
    }

    /** @ignore */
    public function set_views($views)
    {
        $this->views = (int) $views;
    }

    /** @ignore */
    public function get_last_updated()
    {
        return $this->last_updated;
    }

    /** @ignore */
    public function set_last_updated($last_updated)
    {
        $this->last_updated = (int) $last_updated;
    }
}
