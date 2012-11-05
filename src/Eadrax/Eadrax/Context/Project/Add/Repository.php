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

namespace Eadrax\Eadrax\Context\Project\Add;
use Eadrax\Eadrax\Model;

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
     * @param Model\Project $model_project The project to save
     * @return void
     */
    public function add_project(Model\Project $model_project);
}
