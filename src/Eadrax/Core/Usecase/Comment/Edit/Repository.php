<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Edit;

interface Repository
{
    /**
     * @return int
     */
    public function get_comment_author_id($comment_id);

    /**
     * @return void
     */
    public function update_comment($comment_id, $comment_text);
}
