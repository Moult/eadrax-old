<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Data;

class Comment
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int Unique ID of author user who wrote the comment
     */
    public $author;

    /**
     * @var int Unique ID of the update the comment belongs to
     */
    public $update;

    /**
     * @var string
     */
    public $text;
}
