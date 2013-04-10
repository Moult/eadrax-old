<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;

interface Repository
{
    public function project_has_service($project, $service);
    public function add_service_hook_to_project($project, $service);
}
