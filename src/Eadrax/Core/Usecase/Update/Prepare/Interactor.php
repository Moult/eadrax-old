<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Update\Prepare;
use Eadrax\Core\Data;

class Interactor
{
    private $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function interact()
    {
        $this->proposal->validate();

        if ($this->proposal instanceof Data\Image)
        {
            $this->interact_image();
        }
        elseif ($this->proposal instanceof Data\Sound)
        {
            $this->interact_sound();
        }
        elseif ($this->proposal instanceof Data\Video)
        {
            $this->interact_video();
        }
        elseif ($this->proposal instanceof Data\Website)
        {
            $this->interact_website();
        }

        return $this->proposal;
    }

    private function interact_image()
    {
        $this->proposal->generate_thumbnail();
        $this->proposal->calculate_dimensions();
    }

    private function interact_sound()
    {
        $this->proposal->generate_thumbnail();
        $this->proposal->calculate_length();
        $this->proposal->calculate_filesize();
    }

    private function interact_video()
    {
        $this->proposal->encode_to_webm();
        $this->proposal->generate_thumbnail();
        $this->proposal->calculate_length();
        $this->proposal->calculate_filesize();
        $this->proposal->calculate_dimensions();
    }

    private function interact_website()
    {
        $this->proposal->generate_thumbnail();
    }

}
