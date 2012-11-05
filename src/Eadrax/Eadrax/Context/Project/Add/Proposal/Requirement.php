<?php
/**
 * Eadrax Context/Project/Add/Proposal/Requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\Project\Add\Proposal;
use Eadrax\Eadrax\Data;

/**
 * Define data prerequisites to play the proposal role
 *
 * @package    Context
 * @subpackage Role
 */
interface Requirement
{
    /** @ignore */
    public function get_name();
    /** @ignore */
    public function set_name($name);

    /** @ignore */
    public function get_summary();
    /** @ignore */
    public function set_summary($name);

    /** @ignore */
    public function get_author();
    /** @ignore */
    public function set_author(Data\User $author);
}
