<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Delete;

interface Repository
{
    /**
     * @return bool
     */
    public function does_update_have_kudos($update_id, $user_id);

    public function delete_kudos_from_update($user_id, $update_id);
}
