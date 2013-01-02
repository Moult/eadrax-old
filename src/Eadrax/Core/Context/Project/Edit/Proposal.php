<?php

/**
 * Eadrax Context/Project/Edit/Proposal.php
 *
 * @package   Role
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Edit;
use Eadrax\Core\Data;

/**
 * Holds proposal related capabilities
 *
 * @package Role
 */
class Proposal extends Data\Project
{
    /**
     * Sets up role dependencies
     *
     * @return void
     */
    public function __construct(Data\Project $data_project, Repository $repository)
    {
        parent::__construct($data_project);
        $this->repository = $repository;
    }

    /**
     * Updates the current project
     *
     * @return void
     */
    public function update()
    {
        $this->repository->update_project($this);
    }
}
