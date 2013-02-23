<?php
/**
 * Eadrax Usecase/Project/Add/Proposal.php
 *
 * @package   Usecase
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2012 Dion Moult
 * @license   ISC http://opensource.org/licenses/isc-license.txt
 * @link      http://wipup.org/
 */

namespace Eadrax\Core\Usecase\Project\Add;
use Eadrax\Core\Data;
use Eadrax\Core\Usecase;
use Eadrax\Core\Tool;
use Eadrax\Core\Exception;

/**
 * Allows data_project to be cast as a proposal role
 *
 * @package    Usecase
 * @subpackage Role
 */
class Proposal extends Data\Project
{
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
        foreach ($data_project as $property => $value)
        {
            $this->$property = $value;
        }
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
