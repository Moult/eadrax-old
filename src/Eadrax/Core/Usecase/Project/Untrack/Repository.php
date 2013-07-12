<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Untrack;

interface Repository
{
    /**
     * @return bool
     */
    public function does_fan_have_idol($fan_id, $idol_id);

    /**
     * @return bool
     */
    public function does_project_have_fan($project_id, $fan_id);

    /**
     * @return int
     */
    public function get_project_author_id($project_id);

    /**
     * @return void
     */
    public function remove_fan_from_project($project_id);
}
