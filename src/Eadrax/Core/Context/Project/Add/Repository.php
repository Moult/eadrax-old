<?php
/**
 * Eadrax Context/Project/Add/Repository.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Add;
use Eadrax\Core\Data;

/**
 * Handles persistance during adding a project.
 *
 * @package    Context
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

    /**
     * Saves an icon
     *
     * @param Data\File @data_file The file to save
     * @return void
     */
    public function save_icon(Data\File $data_file);
}