<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Track;

interface Repository
{
    public function is_user_tracking_project($fan, $project);
    public function remove_project($fan, $project);
    public function if_fan_of($fan, $idol);
    public function remove_idol($fan, $idol);
    /**
     * @return Array of Data\Project
     */
    public function get_projects_by_author($author);
    /**
     * @param Array $projects Contains Data\project
     */
    public function add_projects($fan, $projects);
    public function number_of_projects_by($author);
    public function number_of_projects_tracked_by($fan, $author);
    public function remove_projects_by_author($fan, $author);
    public function add_project($fan, $project);
    public function get_project_author($project);
}
