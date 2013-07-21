<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Kudos\Delete;

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
            $this->nomination->delete_kudos();
        }
    }
}
