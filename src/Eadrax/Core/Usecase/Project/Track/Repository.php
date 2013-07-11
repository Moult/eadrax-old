<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;

interface Repository
{
    /**
     * @return array($author_id, $author_username)
     */
    public function get_project_author_id_and_username($project_id);

    /**
     * @return void
     */
    public function delete_fan_from_projects_owned_by_user($fan_id, $user_id);

    /**
     * @return array($project_name, $project_author_email)
     */
    public function get_project_name_and_author_email($project_id);

    /**
     * @return int
     */
    public function get_number_of_projects_owned_by_author($author_id);

    /**
     * @return int
     */
    public function get_number_of_tracked_projects_owned_by_author($fan_id, $author_id);

    /**
     * @return bool
     */
    public function does_project_have_fan($project_id, $fan_id);

    /**
     * @return void
     */
    public function add_fan_to_project($fan_id, $project_id);
}
