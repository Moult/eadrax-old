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

        $idol_id = $this->idol->get_id();

        if ($this->fan->has_idol($idol_id))
            return;

        $this->fan->remove_tracked_projects_by($idol_id);
        $this->fan->add_idol($idol_id);
        $this->idol->notify_new_fan($this->fan->get_id());
    }
}
