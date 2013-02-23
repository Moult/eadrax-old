<?php

/**
 * Eadrax Usecase/Project/Edit/Repository.php
 *
 * @package   Repository
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\Project\Edit;
use Eadrax\Core\Data;

interface Repository
{
    public function update_project(Data\Project $data_project);
}
