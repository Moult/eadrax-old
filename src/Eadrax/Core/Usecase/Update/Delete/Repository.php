<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;

interface Repository
{
    public function get_update_type_and_content($update_id);
    public function delete_update($update);
}
