<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;

interface Repository
{
    /**
     * @return bool
     */
    public function does_fan_have_idol($fan_id, $idol_id);

    /**
     * @return void
     */
    public function add_idol_to_fan($idol_id, $fan_id);

    /**
     * @return void
     */
    public function remove_tracked_projects_authored_by_idol($fan_id, $idol_id);

    /**
     * @return array($username, $email)
     */
    public function get_username_and_email($user_id);

    /**
     * @return string
     */
    public function get_username($user_id);
}
