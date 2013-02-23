<?php
/**
 * Eadrax Usecase/Project/Add/Repository.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\Project\Add;
use Eadrax\Core\Data;

/**
 * Handles persistance during adding a project.
 *
 * @package    Usecase
 * @subpackage Repository
 */
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
