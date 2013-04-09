<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;

class Interactor
{
    private $idol;
    private $fan;

    public function __construct(Idol $idol, Fan $fan)
    {
        $this->idol = $idol;
        $this->fan = $fan;
    }

    public function interact()
    {
        $this->fan->authorise();
        if ($this->fan->has_idol($this->idol))
        {
            $this->fan->remove_idol($this->idol);
        }
        else
        {
            $this->fan->remove_tracked_projects_by($this->idol);
            $this->fan->add_idol($this->idol);
            $this->idol->notify_new_fan($this->fan);
        }
    }
}
