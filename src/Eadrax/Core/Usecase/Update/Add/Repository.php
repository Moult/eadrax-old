<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

interface Repository
{
    /**
     * @return array($project_name, $author_id, $author_username);
     */
    public function get_project_name_and_author_id_and_username($project_id);

    /**
     * @return int Unique ID of the saved update
     */
    public function save_text($project_id, $update_private, $text_message);
}
