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
     * @return string ID of newly added project
     */
    public function add(Proposal $proposal);
}
