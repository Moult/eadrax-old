<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Data;

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
