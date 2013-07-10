<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;

interface Repository
{
    /**
     * Checks whether or not a project already has a service url attached to it
     *
     * Example:
     * $repository->has_existing_service(42, 'http://mysite.com/myrss/');
     *
     * @param int    $project_id  The id of the project
     * @param string $service_url The URL of the service feed
     *
     * @return bool
     */
    public function project_has_service($project_id, $service_url);

    /**
     * Adds a service url to a project
     *
     * Example:
     * $repository->add_service_hook(42, 'http://mysite.com/myrss/');
     *
     * @param int    $project_id  The id of the project
     * @param string $service_url The URL of the service feed
     *
     * @return void
     */
    public function add_service_hook($project_id, $service_url);

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
}
