<?php
/**
 * Eadrax application/classes/context/project/add/proposal.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Allows model_project to be cast as a proposal role
 *
 * @package    Context
 * @subpackage Role
 */ 
class Context_Project_Add_Proposal extends Model_Project implements Context_Project_Add_Proposal_Requirement
{
    use Context_Interaction, Context_Project_Add_Proposal_Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Model_Project $model_project Data object to copy
     * @return void
     */
    public function __construct(Model_Project $model_project)
    {
        parent::__construct(get_object_vars($model_project));
    }
}
