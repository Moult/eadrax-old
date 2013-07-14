<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Add;

interface Repository
{
    /**
     * @return bool
     */
    public function does_update_have_kudos($update_id, $user_id);

    /**
     * @return void
     */
    public function add_kudos_to_update($user_id, $update_id);

    /**
     * @return array($project_id, $project_name, $author_id, $author_username, $author_email)
     */
    public function get_project_id_and_name_and_author_id_and_name_and_email($update_id);
}
