<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Comment\Add;

interface Repository
{
    /**
     * @return void
     */
    public function add_comment($text, $author_id, $update_id);

    /**
     * @return bool
     */
    public function does_update_exist($update_id);
}
