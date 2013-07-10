<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;

interface Repository
{
    /**
     * @return bool TRUE if a project already has a service url added to it
     */
    public function has_existing_service($project_id, $service_url);

    /**
     * @return void
     */
    public function add_service_hook($project_id, $service_url);

    /**
     * @return int The ID of the user who is the project author
     */
    public function get_project_author_id($project_id);
}
