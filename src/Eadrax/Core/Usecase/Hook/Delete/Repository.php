<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;

interface Repository
{
    /**
     * @return int The ID of the user who is the project author
     */
    public function get_project_author_id($project_id);


    /**
     * @return void
     */
    public function delete_service_hook($project_id, $service_id);
}
