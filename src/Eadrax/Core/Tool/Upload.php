<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;
use Eadrax\Core\Data;

interface Upload
{
    /**
     * Saves an uploaded file to a specific location.
     *
     * Automatically prefixes filename with unique identifier.
     *
     * Example:
     * $upload->save($file);
     *
     * @return mixed string bool full path to uploaded file or FALSE if failed
     */
    public function save(Data\File $file);
}
