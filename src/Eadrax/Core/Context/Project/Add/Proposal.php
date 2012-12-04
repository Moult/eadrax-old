<?php
/**
 * Eadrax Context/Project/Add/Proposal.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Context\Project\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Context;
use Eadrax\Core\Entity;
use Eadrax\Core\Exception;

/**
 * Allows data_project to be cast as a proposal role
 *
 * @package    Context
 * @subpackage Role
 */
class Proposal extends Data\Project
{
    use Context\Interaction;

    /**
     * Takes a data object and copies all of its properties
     *
     * @param Data\Project $data_project Data object to copy
     * @return void
     */
    public function __construct(Data\Project $data_project = NULL)
    {
        parent::__construct(get_object_vars($data_project));
    }

    /**
     * Sets the author of the project proposal.
     *
     * @param Data\User $data_user
     * @return void
     */
    public function assign_author(Data\User $data_user)
    {
        $this->set_author($data_user);
        return $this->validate_information();
    }

    /**
     * Validates the proposed data in this new project
     *
     * @return void
     */
    public function validate_information()
    {
        $this->setup_validation();

        if ($this->entity_validation->check())
            return $this->icon->exists();
        else
            throw new Exception\Validation($this->entity_validation->errors());
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

    /**
     * Set up the validation criteria
     *
     * @return void
     */
    private function setup_validation()
    {
        $this->entity_validation->setup(array(
            'name' => $this->get_name(),
            'summary' => $this->get_summary(),
            'website' => $this->get_website()
        ));
        $this->entity_validation->rule('name', 'not_empty');
        $this->entity_validation->rule('summary', 'not_empty');
        $this->entity_validation->rule('website', 'url');
    }
}
