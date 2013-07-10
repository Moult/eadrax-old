<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;

interface Repository
{
    /**
     * Returns the ID of the project author
     *
     * Example:
     * $repository->get_project_author_id(42);
     *
     * @param int $project_id The ID of the project
     *
     * @return int The ID of the user who is the project author
     */
    public function get_project_author_id($project_id);


    /**
     * Deletes a service from a project
     *
     * Example:
     * $repository->delete_service_hook(1, 2);
     *
     * @param int $project_id The ID of the project to delete from
     * @param int $service_id The ID of the service to delete
     *
     * @return void
     */
    public function delete_service_hook($project_id, $service_id);
}
