<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Untrack;

interface Repository
{
    /**
     * @return bool
     */
    public function does_idol_have_fan($idol_id, $fan_id);

    /**
     * @return void
     */
    public function add_fan_to_idol($fan_id, $idol_id);
}
