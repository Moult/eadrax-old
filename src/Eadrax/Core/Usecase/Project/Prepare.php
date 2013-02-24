<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project;

use Eadrax\Core\Usecase\Project\Prepare\Proposal;
use Eadrax\Core\Usecase\Project\Prepare\Icon;
use Eadrax\Core\Usecase\Project\Prepare\Repository;
use Eadrax\Core\Usecase\Project\Prepare\Interactor;
use Eadrax\Core\Data;
use Eadrax\Core\Tool;

class Prepare
{
    private $data_project;
    private $data_file;
    private $repository;
    private $tool_image;
    private $tool_validation;

    public function __construct(Data\Project $data_project, Repository $repository, Tool\Validation $tool_validation, Tool\Image $tool_image)
    {
        $this->data_project = $data_project;
        $this->data_file = $data_project->get_icon();
        $this->repository = $repository;
        $this->tool_image = $tool_image;
        $this->tool_validation = $tool_validation;
    }

    public function fetch()
    {
        return new Interactor($this->get_proposal(), $this->get_icon());
    }

    private function get_proposal()
    {
        return new Proposal($this->data_project, $this->tool_validation);
    }

    private function get_icon()
    {
        return new Icon($this->data_file, $this->repository, $this->tool_image, $this->tool_validation);
    }
}
