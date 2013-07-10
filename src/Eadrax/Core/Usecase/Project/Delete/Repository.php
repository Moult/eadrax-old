<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Delete;

interface Repository
{
    /**
     * @return int The unique ID of the project's author
     */
    public function get_project_author_id($project_id);

    /**
     * @return void
     */
    public function delete($project_id);
}
