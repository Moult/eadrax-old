<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Edit;
use Eadrax\Core\Data;

interface Proposal
{
    public function authorise_ownership();
    public function load_prepared_proposal(Data\Update $proposal);
    public function submit();
}
