<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;
use Eadrax\Core\Data;

class Service extends Data\Hook
{
    public function __construct(Data\Hook $hook)
    {
        $this->id = $hook->id;
    }
}
