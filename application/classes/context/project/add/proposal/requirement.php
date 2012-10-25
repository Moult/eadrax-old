<?php
/**
 * Eadrax application/classes/context/project/add/proposal/requirement.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Define data model prerequisites to play the proposal role
 *
 * @package    Context
 * @subpackage Role
 */
interface Context_Project_Add_Proposal_Requirement
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
    public function set_author(Model_User $author);
}
