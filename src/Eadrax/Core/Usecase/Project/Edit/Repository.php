<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Edit;
use Eadrax\Core\Data;

interface Repository
{
    /**
     * @return int The unique ID of the project's author
     */
    public function get_project_author_id($project_id);

    /**
     * @return void
     */
    public function update($project_id, $project_name, $project_summary, $project_description, $project_website, $project_last_updated);
}
