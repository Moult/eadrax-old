<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;

interface Repository
{
    public function get_project_author($project);
    public function delete_service_hook_from_project($project, $service);
}
