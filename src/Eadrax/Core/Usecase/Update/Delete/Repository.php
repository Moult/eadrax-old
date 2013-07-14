<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;

interface Repository
{
    /**
     * @return int Unique ID of the update's project's author
     */
    public function get_author_id($update_id);

    /**
     * @return void
     */
    public function purge_files($update_id);

    /**
     * @return void
     */
    public function delete_update($update_id);
}
