<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

namespace Eadrax\Core\Usecase\Project\Prepare;

use Eadrax\Core\Exception;

class Interactor
{
    private $proposal;
    private $icon;

    public function __construct(Proposal $proposal, Icon $icon)
    {
        $this->proposal = $proposal;
        $this->icon = $icon;
    }

    public function interact()
    {
        $this->proposal->validate_information();
        if ($this->icon->exists())
        {
            $this->icon->validate_information();
            $this->icon->upload();
        }
    }

    public function execute()
    {
        try
        {
            $this->interact();
        }
        catch (Exception\Validation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'validation',
                'data' => array(
                    'errors' => $e->get_errors()
                )
            );
        }

        return array(
            'status' => 'success'
        );
    }
}
