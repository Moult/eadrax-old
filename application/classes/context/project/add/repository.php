<?php
/**
 * Eadrax application/classes/context/project/add/repository.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Handles persistance during adding a project.
 *
 * @package    Context
 * @subpackage Repository
 */
class Context_Project_Add_Repository
{
    /**
     * For MySQL project table interactions.
     * @var Gateway_Project
     */
    public $gateway_mysql_project;

    /**
     * Sets up DAOs
     *
     * @return void
     */
    public function __construct()
    {
        $this->gateway_mysql_project = new Gateway_Mysql_Project;
    }

    /**
     * Saves a project in the database
     *
     * @param Model_Project $model_project The project to save
     * @return void
     */
    public function add_project($model_project)
    {
        $this->gateway_mysql_project->insert(array(
            'name' => $model_project->name,
            'summary' => $model_project->summary,
            'author' => $model_project->author->id
        ));
    }
}
