<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Add;
use Eadrax\Core\Data;

interface Repository
{
    /**
     * @return int Unique ID of newly added project
     */
    public function add($project_name, $project_summary, $author_id, $project_last_updated);
}
