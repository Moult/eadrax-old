<?php
/**
 * Eadrax application/classes/model/project.php
 *
 * @package   Model
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * This is a generic WIPUP project.
 *
 * @package Model
 */
class Model_Project extends Model_Core
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
        return $this->author;
    }

    /** @ignore */
    public function set_author(Model_User $author)
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
        return $this->icon;
    }

    /** @ignore */
    public function set_icon($icon)
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
        $this->views = $views;
    }

    /** @ignore */
    public function get_last_updated()
    {
        return $this->last_updated;
    }

    /** @ignore */
    public function set_last_updated($last_updated)
    {
        $this->last_updated = $last_updated;
    }
}
