<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Delete;

class Interactor
{
    private $project;
    private $service;

    public function __construct(Project $project, Service $service)
    {
        $this->project = $project;
        $this->service = $service;
    }

    public function interact()
    {
        $this->project->authorise();
        $this->project->remove_service($this->service);
    }
}
