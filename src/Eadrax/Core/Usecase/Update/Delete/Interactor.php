<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Delete;

class Interactor
{
    private $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->proposal->authorise();
        $this->proposal->delete();
    }
}
