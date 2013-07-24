<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Delete;

interface Repository
{
    /**
     * @return int
     */
    public function get_comment_author_id($comment_id);

    /**
     * @return void
     */
    public function delete_comment($comment_id);
}
