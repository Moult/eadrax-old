<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Delete;

interface Repository
{
    /**
     * @return Eadrax\Core\Data\User
     */
    public function get_owner(Proposal $proposal);

    public function delete(Proposal $proposal);
}
