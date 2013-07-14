<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Add;

class Interactor
{
    private $nomination;

    public function __construct(Nomination $nomination)
    {
        $this->nomination = $nomination;
    }

    public function interact()
    {
        if ($this->nomination->has_kudos())
        {
            $this->nomination->add_kudos();
            $this->nomination->notify_author();
        }
    }
}
