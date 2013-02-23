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
     * Saves a project
     *
     * @param Data\Project $data_project The project to save
     * @return void
     */
    public function add_project(Data\Project $data_project);
}
