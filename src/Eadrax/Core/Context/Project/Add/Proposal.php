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
     * Project add repository
     * @var Repository
     */
    private $repository;

    /**
     * Imports data and sets up collaborators
     *
     * @param Data\Project $data_project Data object to copy
     * @param Repository   $repository   Project add repository
     * @return void
     */
    public function __construct(Data\Project $data_project, Repository $repository)
    {
        parent::__construct($data_project);
        $this->repository = $repository;
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
