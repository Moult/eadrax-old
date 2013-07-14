<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Usecase;

class Interactor
{
    private $proposal;
    private $update_prepare;

    public function __construct(Proposal $proposal, Usecase\Update\Prepare\Interactor $update_prepare)
    {
        $this->proposal = $proposal;
        $this->update_prepare = $update_prepare;
    }

    public function interact()
    {
        $this->proposal->authorise_ownership();
        $this->proposal->load_prepared_proposal($this->update_prepare->interact());
        $this->proposal->submit();
    }
}
