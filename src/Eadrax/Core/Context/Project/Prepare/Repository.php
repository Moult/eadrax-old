<?php
/**
 * Eadrax Context/Project/Prepare/Repository.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Prepare;
use Eadrax\Core\Data;

/**
 * Handles persistance when preparing a project.
 *
 * @package    Context
 * @subpackage Repository
 */
interface Repository
{
    /**
     * Saves an icon
     *
     * @param Data\File $data_file The file to save
     * @return void
     */
    public function save_icon(Data\File $data_file);
}
