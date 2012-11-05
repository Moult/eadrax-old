<?php
/**
 * Eadrax Context/Project/Add/Proposal/Interaction.php
 *
 * @package   Context
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Eadrax\Context\Project\Add\Proposal;
use Eadrax\Eadrax\Data;
use Eadrax\Eadrax\Exception;

/**
 * Defines what the proposal role is capable of.
 *
 * @package    Context
 * @subpackage Interaction
 */
trait Interaction
{
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
        $this->entity_validation->setup(array(
            'name' => $this->get_name(),
            'summary' => $this->get_summary()
        ));
        $this->entity_validation->rule('name', 'not_empty');
        $this->entity_validation->rule('summary', 'not_empty');
        if ($this->entity_validation->check())
            return $this->submit();
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
}
