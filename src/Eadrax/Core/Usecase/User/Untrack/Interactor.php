<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Untrack;

class Interactor
{
    private $fan;
    private $idol;

    public function __construct(Fan $fan, Idol $idol)
    {
        $this->fan = $fan;
        $this->idol = $idol;
    }

    public function interact()
    {
        $this->fan->authorise();
        if ( ! $this->idol->has_fan())
            return $this->idol->add_fan();
    }
}
