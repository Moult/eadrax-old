<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;
use Eadrax\Core\Data;

interface Proposal
{
    public function load_prepared_proposal(Data\Update $proposal);
    public function submit();
    public function get_id();
}
