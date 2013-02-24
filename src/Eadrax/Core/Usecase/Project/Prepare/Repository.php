<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Data;

interface Repository
{
    public function save_icon(Data\File $data_file);
}
