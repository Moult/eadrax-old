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

    /**
     * @return array(
     *             'author_username' => $author_username,
     *             'author_email' => $author_email,
     *             'project_id' => $project_id,
     *             'project_name' => $project_name
     *         );
     */
    public function get_update_details($update_id);
}
