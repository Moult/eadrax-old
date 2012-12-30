<?php

namespace Eadrax\Core\Context\Project\Prepare;
use Eadrax\Core\Exception;

class Interactor
{
    /**
     * Proposal role
     * @var Proposal
     */
    private $proposal;

    /**
     * Icon role
     * @var Icon
     */
    private $icon;

    public function __construct(Proposal $proposal, Icon $icon)
    {
        $this->proposal = $proposal;
        $this->icon = $icon;
    }

    /**
     * Runs the interaction chain
     *
     * @throws Exception\Validation
     * @return void
     */
    public function interact()
    {
        $this->proposal->validate_information();
        if ($this->icon->exists())
        {
            $this->icon->validate_information();
            $this->icon->upload();
        }
    }

    /**
     * Generates a result array out of the interaction chain
     *
     * @return array
     */
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
