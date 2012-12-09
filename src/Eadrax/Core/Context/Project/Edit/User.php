<?php

namespace Eadrax\Core\Context\Project\Edit;

use Eadrax\Core\Context\Interaction;
use Eadrax\Core\Data;
use Eadrax\Core\Exception;

class User extends Data\User
{
    use Interaction;

    public function __construct(Data\User $data_user)
    {
        parent::__construct(get_object_vars($data_user));
    }

    public function authorise_project_edit()
    {
        if ($this->entity_auth->logged_in())
            return $this->check_proposal_author();
        else
            throw new Exception\Authorisation('You need to be logged in to edit a project.');
    }

    private function check_proposal_author()
    {
        if ($this->proposal->get_author()->get_id() === $this->entity_auth->get_user()->get_id())
            return $this->proposal->set_author($this);
        else
            throw new Exception\Authorisation('You cannot edit a project you do not own.');
    }
}
