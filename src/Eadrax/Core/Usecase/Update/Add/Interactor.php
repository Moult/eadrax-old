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
        $this->generate_thumbnails();
        $this->proposal->submit();
        $this->notify_trackers();
    }

    private function process_files()
    {
        if ($this->proposal->type === 'file')
        {
            $this->proposal->detect_file_type();
            $this->proposal->upload();
            $this->proposal->encode_video();
            $this->proposal->generate_metadata();
        }
    }

    private function generate_thumbnails()
    {
        if ($this->proposal->type === 'website'
            OR $this->proposal->type === 'file')
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
