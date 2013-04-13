<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

interface Repository
{
    public function get_project_author($project);
    /**
     * @return Array of Data\User
     */
    public function get_user_and_project_trackers($project);
}
