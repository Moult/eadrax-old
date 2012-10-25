<?php
/**
 * Eadrax application/classes/context/project/add/proposal/interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

defined('SYSPATH') OR die('No direct script access.');

/**
 * Defines what the proposal role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Context_Project_Add_Proposal_Interaction
{
    /**
     * Sets the author of the project proposal.
     *
     * @param Model_User $model_user
     * @return void
     */
    public function set_author(Model_User $model_user)
    {
        $this->author = $model_user;
        return $this->validate_information();
    }

    /**
     * Validates the proposed data in this new project
     *
     * @return void
     */
    public function validate_information()
    {
        $validation = Validation::factory(get_object_vars($this))
            ->rule('name', 'not_empty')
            ->rule('summary', 'not_empty');
        if ($validation->check())
            return $this->submit();
        else
            throw new Exception_Validation($validation->errors());
    }

    /**
     * Submits the proposal to the repository
     *
     * @return void
     */
    public function submit()
    {
        return $this->repository->add_project($this);
    }
}
