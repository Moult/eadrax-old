<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Tool;

interface Filemanager
{
    /**
     * Retrieves the filesize of a file in bytes
     *
     * Example:
     * $file_size = $filemanager->get_file_size('path/to/file');
     *
     * @return int
     */
    public function get_file_size($path_to_file);
}
