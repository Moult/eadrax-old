<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\User\Track;

class Interactor
{
    private $idol;
    private $user;

    public function __construct(Idol $idol, User $user)
    {
        $this->idol = $idol;
        $this->user = $user;
    }

    public function interact()
    {
        $this->user->authorise();
        if ($this->user->has_idol($this->idol))
        {
            $this->user->remove_idol($this->idol);
        }
        else
        {
            $this->user->add_idol($this->idol);
            $this->idol->notify();
        }
    }
}
