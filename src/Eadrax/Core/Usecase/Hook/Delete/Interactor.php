<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;
use Eadrax\Core\Data;

class Interactor
{
    private $hook;
    private $project;

    public function __construct(Data\Hook $hook, Project $project)
    {
        $this->hook = $hook;
        $this->project = $project;
    }

    public function interact()
    {
        $this->project->authorise();
        $this->project->remove_service($this->hook->id);
    }
}
