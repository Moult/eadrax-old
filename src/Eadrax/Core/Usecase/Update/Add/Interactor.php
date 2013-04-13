<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Add;

class Interactor
{
    private $project;
    private $proposal;

    public function __construct(Project $project, Proposal $proposal)
    {
        $this->project = $project;
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->project->authorise();
        $this->proposal->validate();
        $this->process_files();
        $this->proposal->submit();
        $this->notify_trackers();
    }

    private function process_files()
    {
        if ($this->proposal->type === 'file')
        {
            $this->proposal->upload();
            $this->generate_thumbnail_if_file_can_be_visualised();
            $this->proposal->generate_metadata();
        }
    }

    private function generate_thumbnail_if_file_can_be_visualised()
    {
        if ($this->proposal->extra === 'image'
            OR $this->proposal->extra === 'video'
            OR $this->proposal->extra === 'sound')
        {
            $this->proposal->generate_thumbnail();
        }
    }

    private function notify_trackers()
    {
        if ( ! $this->proposal->private)
        {
            $this->project->notify_trackers($this->proposal);
        }
    }
}
