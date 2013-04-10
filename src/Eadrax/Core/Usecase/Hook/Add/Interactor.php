<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Hook\Add;

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
        if ( ! $this->project->has_service($this->service))
        {
            $this->service->is_valid();
            $this->project->add_service($this->service);
        }
    }
}
