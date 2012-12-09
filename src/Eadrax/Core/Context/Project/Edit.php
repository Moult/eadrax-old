<?php

namespace Eadrax\Core\Context\Project;

use \Eadrax\Core\Context\Core;
use \Eadrax\Core\Context\Project\Edit\User;
use \Eadrax\Core\Context\Project\Edit\Proposal;
use \Eadrax\Core\Context\Project\Edit\Repository;
use \Eadrax\Core\Data;
use \Eadrax\Core\Exception;
use \Eadrax\Core\Entity;

class Edit extends Core
{
    function __construct(Data\Project $data_project, Entity\Auth $entity_auth)
    {
        $this->proposal = new Proposal($data_project);
        $this->user = new User($data_project->get_author());

        $this->user->link(array(
            'proposal' => $this->proposal,
            'entity_auth' => $entity_auth
        ));
    }

    public function execute()
    {
        try {
            $this->user->authorise_project_edit();
        }
        catch (Exception\Authorisation $e)
        {
            return array(
                'status' => 'failure',
                'type' => 'authorisation',
                'data' => array(
                    'errors' => array(
                        $e->getMessage()
                    )
                )
            );
        }
    }
}
