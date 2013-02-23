<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Data;

class Project
{
    public $name;
    public $summary;
    /**
     * @var User
     */
    public $author;
    public $description;
    public $website;
    public $views;
    public $last_updated;
}
